FROM ubuntu:18.04

# Set env to noninteractive
ENV DEBIAN_FRONTEND=noninteractive

# Install dependencies
RUN apt-get update && apt-get install -y \
    wget \
    net-tools \
    lsof \
    curl \
    unzip \
    libaio1 \
    ca-certificates \
    libncurses5 \
    libnss3 \
    libx11-6 \
    libxrender1 \
    libxtst6 \
    libxi6 \
    xz-utils

# Download and install XAMPP
RUN wget https://downloads.sourceforge.net/project/xampp/XAMPP%20Linux/7.3.2/xampp-linux-x64-7.3.2-0-installer.run && \
    chmod +x xampp-linux-x64-7.3.2-0-installer.run && \
    ./xampp-linux-x64-7.3.2-0-installer.run --mode unattended && \
    rm xampp-linux-x64-7.3.2-0-installer.run

# Add XAMPP to PATH
ENV PATH="/opt/lampp:${PATH}"

# Create ShelfControl directory and move app files there
RUN mkdir -p /opt/lampp/htdocs/ShelfControl

# Change working directory for subsequent COPY
#WORKDIR /opt/lampp/htdocs/ShelfControl
# Copy your app files
COPY . /opt/lampp/htdocs/ShelfControl

# Expose Apache port
EXPOSE 80
# Install Composer globall y
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Run composer dump-autoload
#RUN cd /opt/lampp/htdocs/ShelfControl && composer dump-autoload

# Start XAMPP (Apache + MySQL + ProFTPD)
CMD ["/opt/lampp/bin/apachectl", "-D", "FOREGROUND"]
