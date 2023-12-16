Vagrant.configure("2") do |config|
  config.vm.box = "bento/ubuntu-22.04-arm64"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "private_network", ip: "192.168.59.10"
  config.vm.synced_folder "./nginx", "/etc/nginx/conf.d"

  config.vm.provision "shell", inline: <<-SHELL
    apt-get update

    apt-get install -y nginx
    rm -f /etc/nginx/sites-enabled/default
    systemctl restart nginx

    apt-get install -y php-fpm
  SHELL
end
