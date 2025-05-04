# Define a imagem base com PHP 8.3 e FPM (FastCGI Process Manager)
FROM php:8.3-fpm

# Define o mantenedor da imagem (este comando está obsoleto)
MAINTAINER Lucas Santos

# Atualiza a lista de pacotes e instala pacotes necessários, incluindo a extensão zip
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libldap2-dev \
    zip \
    unzip \
    libzip-dev

# Limpa o cache do apt-get para economizar espaço
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP necessárias, incluindo a extensão zip
RUN docker-php-ext-configure zip && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd soap zip

# Instala o Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Cria um grupo de usuários chamado 'www' com o ID de grupo 1000
RUN groupadd -g 1000 www

# Cria um usuário chamado 'www' com o ID de usuário 1000 e atribui ao grupo 'www'
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copia o conteúdo do diretório atual para /var/www/html no container, com permissões do usuário e grupo 'www'
COPY --chown=www:www . /var/www/html

# Copia o script de inicialização para o diretório binário local
COPY /environment/backend/start.sh /usr/local/bin/start

# Torna o script de inicialização executável
RUN chmod +x /usr/local/bin/start

# Define o usuário padrão para o restante das instruções e para o container em execução
USER www

# Define o comando padrão a ser executado quando o container é iniciado
CMD ["/usr/local/bin/start"]
