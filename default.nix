# [Nix](https://nixos.org) based development environment using PHP Serve
# 1. Install Nix: `sh <(curl -L https://nixos.org/nix/install)`
# 2. Run command: `nix-shell`

{ pkgs ? import <nixpkgs> {}}:

let myPhp = pkgs.php83.buildEnv {
    extensions = ({ enabled, all }: enabled ++ [ all.xdebug ] ++ [ all.apcu ]);
    extraConfig = ''
        memory_limit=128M
        upload_max_filesize=256M
        post_max_size=256M
        apc.enable_cli=1
    '';
};
in pkgs.mkShell {
    packages = [
        myPhp
        myPhp.packages.composer
    ];

    shellHook = ''
        composer install
    '';
}
