class vim {
    package { "vim":
        ensure => present
    }

    exec { "vim-config":
        command => "/usr/bin/git clone https://github.com/stephpy/vim-config.git /home/vagrant/.vim-config && ln -s /home/vagrant/.vim-config/.vim /home/vagrant/.vim && ln -s /home/vagrant/.vim-config/.vimrc /home/vagrant/.vimrc",
        require => Package["git-core"]
    }

    exec { "clone-vundle":
        command => "/usr/bin/git clone http://github.com/gmarik/vundle.git /home/vagrant/.vim/bundle/vundle",
        creates => "/home/vagrant/.vim/bundle/vundle/README.md",
        require => Package["git-core"]
    }

    file { "/home/vagrant":
        recurse => true,
        owner => "vagrant",
        group => "vagrant",
        mode => 644,
        require => Exec["clone-vundle"]
    }

    exec { "vundle-bundle-install":
        command => "/bin/su -l vagrant -c '/usr/bin/vim +BundleInstall +qall'",
        require => [
            Package["vim"],
            Exec["clone-vundle"],
            Exec["vim-config"]
        ]
    }

    # To compile with Ruby
    # 
    # exec { "compile-command-t":
    #     cwd => "/home/vagrant/.vim/bundle/Command-T/ruby/command-t",
    #     command => "/usr/bin/ruby extconf.rb && make",
    #     require => Exec["vundle-bundle-install"]
    # }
}
