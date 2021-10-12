```
Settings > Developers > Linux development environment > Turn on
Open a new crosh terminal in Chrome (Ctrl + Alt + T)
vmc destroy termina
vmc start termina
exit
vmc container termina penguin https://us.images.linuxcontainers.org archlinux/current

Error: operation `container_start` failed: timeout while waiting for signal
[ERROR:src/main.rs:181] ERROR: command failed

vsh termina
lxc list
lxc exec penguin -- bash
grep 1000:1000 /etc/passwd | cut -d ':' -f 1
pkill -9 -u $(grep 1000:1000 /etc/passwd | cut -d ':' -f 1)
groupmod -n uncon $(grep 1000:1000 /etc/passwd | cut -d ':' -f 1)
usermod -d /home/new-username -l uncon -m -c new-username $(grep 1000:1000 /etc/passwd | cut -d ':' -f 1)
passwd uncon
visudo
usermod -aG wheel uncon
exit
lxc console penguin
sudo pacman -Sy
sudo pacman -S base-devel git wayland xorg-xwayland ttf-croscore noto-fonts noto-fonts-cjk noto-fonts-emoji noto-fonts-extra
mkdir -p ~/aur
cd ~/aur
PKG="paru-bin" && git clone "https://aur.archlinux.org/${PKG}.git/" && cd "${PKG}" && makepkg -i -s -r --skippgpcheck && cd && rm -fr ~/aur
paru -S cros-container-guest-tools-git
cp -r /etc/skel/.config/pulse ~/.config
sudo reboot
```
