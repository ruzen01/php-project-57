{pkgs}: {
  deps = [
    pkgs.php82Extensions.xdebug
    pkgs.php82Extensions.pcov
    pkgs.mkcert
  ];
}
