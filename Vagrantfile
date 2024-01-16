Vagrant.configure("2") do |config|
  config.vm.box = "bento/ubuntu-22.04-arm64"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "forwarded_port", guest: 3306, host: 3306
  config.vm.network "private_network", ip: "192.168.59.10"
  config.vm.synced_folder "./nginx", "/etc/nginx/conf.d"

  config.vm.provision "shell", inline: <<-SHELL
    apt-get update

    apt-get install -y nginx
    rm -f /etc/nginx/sites-enabled/default
    systemctl restart nginx

    apt-get install -y php-fpm

    apt-get install -y mysql-server
    mysql -e "CREATE USER IF NOT EXISTS 'vagrant'@'%' IDENTIFIED BY 'vagrant';"
    mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'vagrant'@'%';"
    mysql -e "FLUSH PRIVILEGES;"
    sed -i '/bind-address/s/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf
    systemctl restart mysql

    apt-get install -y php-mysql
  SHELL
end
