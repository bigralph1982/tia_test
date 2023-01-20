# chmod +x deploy.sh

# red=`tput setaf 1`
# reset=`tput sgr0`

# 0    black     COLOR_BLACK     0,0,0
# 1    red       COLOR_RED       1,0,0
# 2    green     COLOR_GREEN     0,1,0
# 3    yellow    COLOR_YELLOW    1,1,0
# 4    blue      COLOR_BLUE      0,0,1
# 5    magenta   COLOR_MAGENTA   1,0,1
# 6    cyan      COLOR_CYAN      0,1,1
# 7    white     COLOR_WHITE     1,1,1

# tput bold    # Select bold mode
# tput dim     # Select dim (half-bright) mode
# tput smul    # Enable underline mode
# tput rmul    # Disable underline mode
# tput rev     # Turn on reverse video mode
# tput smso    # Enter standout (bold) mode
# tput rmso    # Exit standout mode


# tput ech N   # Erase N characters
# tput clear   # Clear screen and move the cursor to 0,0
# tput el 1    # Clear to beginning of line
# tput el      # Clear to end of line
# tput ed      # Clear to end of screen
# tput ich N   # Insert N characters (moves rest of line forward!)
# tput il N    # Insert N lines


# tput sgr0    # Reset text format to the terminal's default
# tput bel     # Play a bell


# tput bel;



# tput setaf 3;

# tput sgr 3;

red="\033[31;7m";
green="\033[32;7m";
yellow="\033[33;7m";
blue="\033[34;7m";


# echo -e "$yellow";




tput bold;
echo -e "$yellow Deploying..\n"
tput sgr0


tput bold;
echo -e "$blue[1]$yellow Updating repo..\n"
tput sgr0
git pull


tput bold;
echo -e "$blue[2]$yellow Updating schema..\n"
tput sgr0
bin/console doctrine:database:create --if-not-exists
bin/console doctrine:schema:update --force

tput bold;
echo -e "$blue[3]$yellow Loading fixtures..\n"
tput sgr0
bin/console doctrine:fixtures:load --append

tput bold;
echo -e "$blue[4]$yellow Creating missing folders..\n"
tput sgr0
mkdir -p var/cache
mkdir -p var/log
mkdir -p var/sessions
mkdir -p public/uploads
mkdir -p public/media

tput bold;
echo -e "$blue[5]$yellow Solving permissions..\n"
tput sgr0
sudo chmod -R 777 var/cache
sudo chmod -R 777 var/log
sudo chmod -R 777 var/sessions
sudo chmod -R 777 public/uploads
sudo chmod -R 777 public/media

tput bold;
echo -e "$blue[6]$yellow Removing cache prod..\n"
tput sgr0
sudo rm -rf var/cache/prod

# # creating testroot with pass encrypted "openssl passwd -crypt"
# useradd -m -p 7IbJkNSoyJTG2 -s /bin/bash syncroot
# usermod -aG sudo syncroot

# # deleting test user
# deluser testroot
# rm -rf /home/testroot

tput bold;
echo -e "$blue[7]$green Completed..\n"
# tput bel;
# tput sgr0
# notify-send "Process terminated" "Thank you."
