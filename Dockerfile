# Dockerfile otimizado para Laravel 12 + PHP 8.2
# Multi-stage:
# - builder: instala dependências PHP (composer) e JS (npm)
# - runtime: imagem final com php-fpm e nginx, opcache habilitado

########### STAGE: builder (composer + node build) ###########
FROM composer:2.8 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# instalar dependências PHP (sem scripts para evitar side-effects durante build)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts --no-progress

# se houver assets JS, construir em um stage separado para cache efetivo
FROM node:22.11-alpine AS node-builder
WORKDIR /app
COPY package.json package-lock.json* ./
RUN if [ -f package.json ]; then npm ci --silent; fi
COPY . .
RUN if [ -f package.json ]; then npm run build || true; fi


########### STAGE: runtime ###########
FROM php:8.3.14-fpm-alpine AS runtime
WORKDIR /app

# Instalar dependências de sistema e ferramentas básicas
RUN apk add --no-cache \
    nginx \
    bash \
    curl \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make \
    shadow \
    pcre-dev \
    zlib-dev

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql mbstring zip bcmath intl opcache

# (Opcional) extensões adicionais como redis: uncomment se necessário
# RUN pecl install redis && docker-php-ext-enable redis

# Criar usuário www-data (alpine ships with www-data, ajustar se preciso)
RUN usermod -u 1000 www-data || true

# Copiar vendor do stage do composer
COPY --from=vendor /app/vendor ./vendor

# Copiar aplicação
COPY . .

# Copiar assets construídos (se houver)
COPY --from=node-builder /app/public ./public

# Copiar configuração do nginx
COPY deploy/nginx.conf /etc/nginx/nginx.conf

# Ajustes de permissões (garantir que o usuário web consiga escrever)
RUN chown -R www-data:www-data storage bootstrap/cache public || true
RUN chmod -R 0755 storage bootstrap/cache || true

# Configurar environment para produção por padrão (pode ser sobrescrito)
ENV APP_ENV=production \
    APP_DEBUG=false \
    PATH="/root/.composer/vendor/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"

# Expor porta
EXPOSE 80

# Habilitar opcache configurado via ini adicional (opcache otimizado para produção)
RUN { \
    echo "opcache.memory_consumption=256"; \
    echo "opcache.interned_strings_buffer=16"; \
    echo "opcache.max_accelerated_files=10000"; \
    echo "opcache.validate_timestamps=0"; \
    echo "opcache.revalidate_freq=0"; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Healthcheck simples (ajuste conforme necessário)
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s --retries=3 \
    CMD wget -qO- --tries=1 --timeout=2 http://localhost/ || exit 1

# Comando padrão: iniciar php-fpm e nginx (foreground)
CMD ["/bin/sh", "-c", "php-fpm -R & nginx -g 'daemon off;' "]
