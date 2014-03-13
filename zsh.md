# Zsh Stuff
See also, [uncon/dotfiles](https///github.com/uncon/dotfiles).

## Default Shell
Change Shell

	chsh -s $(which zsh)`

## Install Prezto
Below are the steps to install [Prezto](https///github.com/sorin-ionescu/prezto).

1. Run Zsh

		zsh

2. Download

		git clone --recursive https://github.com/sorin-ionescu/prezto.git "${ZDOTDIR:-$HOME}/.zprezto"

3. Install

		setopt EXTENDED_GLOB
		for rcfile in "${ZDOTDIR:-$HOME}"/.zprezto/runcoms/^README.md(.N); do
			ln -s "$rcfile" "${ZDOTDIR:-$HOME}/.${rcfile:t}"
		done

4. Set theme

		printf '\nautoload -Uz promptinit\npromptinit\nprompt adam1\n' >> ~/.zshrc
