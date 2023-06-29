# Installing Yugal
> Inviting contributions to add dynamic routing functionallity in Yugal
## Installing PHP Enviroment
PHP environment is necessary for Yugal projects to test and run. In local computer you can install any PHP environment you are comfortable with. Here we are installing XAMPP.

Installing XAMPP is very easy, just download respective package from https://www.apachefriends.org/index.html suitable for your OS and install it as you do with any other software.  

> Earlier Yugal CLI required Node JS to be installed to run, from Yugal 8, Yugal CLI is moved to Python, so developers now need to install Python.

# Install Yugal CLI
## Install Python
Download and install Python from its [official site](https://www.python.org/downloads/). Make sure that you check `pip` and `Environment Variables` checkbox while installation. In most of Linux distributions Python is pre installed. Therefore if you are using using Linux, then try running `python` or `python3` in your terminal to check if Python is already installed. 

## Install Yugal CLI
After installing Python, run the command below to install Yugal CLI.
```bash
pip install yugal
```

# Creating New Project
Run the command below to create new project in your server directory. Eg: `htdocs`, `public_html`.
```bash
yugal --init awesomeapp
```
[Read Full Documentation](https://yugalphp.gitbook.io/)
