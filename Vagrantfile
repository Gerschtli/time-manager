$name = "time-manager"
$private_ip = "192.168.33.10"

Vagrant.configure(2) do |config|
    config.vm.define $name do |vbox|
        vbox.vm.box = "ubuntu/trusty64"

        vbox.vm.hostname = $name

        vbox.vm.network "private_network", ip: $private_ip
        vbox.vm.synced_folder ".", "/var/www/" + $name, :mount_options => ["dmode=777", "fmode=666"]

        vbox.vm.provider "virtualbox" do |vb|
            vb.name = $name
        end

        vbox.vm.provision :shell do |shell|
            shell.inline = "
                mkdir -p -m 0755 /etc/puppet/modules &&
                touch /etc/puppet/hiera.yaml &&
                (puppet module list | grep puppetlabs-apt >> /dev/null || puppet module install puppetlabs-apt)
            "
        end

        vbox.vm.provision "puppet"
    end
end
