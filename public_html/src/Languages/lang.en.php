<?PHP

namespace src\Languages;

class GetLanguageContent
{
    public $langMap = array();
    public $lang = 'EN';
    
    public function __construct()
    {
        $this->langMap = $this->initBaseLangs();
    }
    
    public function initBaseLangs() // Base langs outgame + homepage
    {
        global $user;
        global $route;
        if($user->notIngame())
        {
            $langs = array(
                'HOME' => "Home",
                'LOGIN' => "Login",
                'REGISTER' => "Register",
                'SCREENSHOTS' => "Screenshots",
                'FORUM' => "Forum",
                'USERNAME' => "Username",
                'PASSWORD' => "Password",
                'END_COPY' => "All rights reserved.",
                'TERMS_AND_CONDITIONS' => "Terms and conditions",
                'PRIVACY_POLICY' => "Privacy policy",
                'OFFLINE_MSG' => "We're currently offline due to maintenance.", //preperations for the coming reset!",
                'ONLINE_MSG' => "There are currently <span class='online'><strong>{online}</strong></span> players online!",
                'PLAYERS_BEFORE_MSG' => "along <span class='total'>{totalPlayers}</span> criminals! Or",
                'REGISTER_BTN' => "Join",
                'CHANGE_LANG_SUCCESS' => "Je hebt de taalinstellingen verandert naar het Nederlands.",
                'COOKIES_ACCEPT' => "Our website uses cookies to improve your browsing experience, <a href='/privacy-policy'><strong>view privacy policy</strong></a> for more information.",
                'LINK_PARTNERS_INFO' => "Get to know our <a href='".$route->getRouteByRouteName('link-partners')."'>link partners</a>.",
                'DOWNLOAD_APP' => "Download the app",
                'WRONG_CAPTCHA' => "You've entered the wrong security code!", // Use in & out-game
                'INVALID_SECURITY_TOKEN' => "Invalid security token, please refresh the page (F5) and try again. If you block essential cookies, no valid security token can be granted.", // Use in & out-game
                'INFORMATION' => "Information", // Use in & out-game
                'TOPLIST' => "Toplist", // Use in & out-game
                'NONE' => "None", // Use in & out-game
            );
        }
        if(!$user->notIngame()) // Base langs ingame
        {
            $langs = array(
                'GENERAL' => "General",
                'INFORMATION' => "Information", // Use in & out-game
                'NEWS' => "News",
                'PRISON' => "Prison",
                'HONOR_POINTS' => "Honor Points",
                'TRAVEL_AGENCY' => "Travel agancy",
                'GAME' => "Game",
                'MARKET' => "Market",
                'STOCK_EXCHANGE' => "Stock Exchange",
                'EQUIPMENT_STORES' => "Equipment Stores",
                'ESTATE_AGENCY' => "Estate Agancy",
                'BULLET_FACTORIES' => "Bullet Factories",
                'HITLIST' => "Hitlist",
                'MURDER' => "Murder",
                'HOSPITAL' => "Hospital",
                'GYM' => "Gym",
                'GROUND_MAP' => "Ground Map",
                'GROUND' => "Ground",
                'MISSIONS' => "Missions",
                'POSSESSION' => "Possession",
                'POSSESSIONS' => "Possessions",
                'DONATION_SHOP' => "Donation Shop",
                'COMMUNICATION' => "Communication",
                'SOCCER_BETTING' => "Soccer Betting",
                'DOBBLING' => "Dobbling",
                'FIFTY_GAMES' => "50/50 Games",
                'SLOT_MACHINE' => "Slot Machine",
                'LOTTERY' => "Lottery",
                'PROFILE' => "Profile",
                'FAMILY' => "Family",
                'STATE' => "State",
                'CITY' => "City",
                'PROFESSION' => "Profession",
                'CASH' => "Cash",
                'HEALTH' => "Health",
                'CRIMES' => "Crimes",
                'STEAL_VEHICLES' => "Steal Vehicles",
                'DRUGS_LIQUIDS' => "Drugs and Liquids",
                'SMUGGLING' => "Smuggling",
                'PROPERTIES' => "Properties",
                'LIST' => "List",
                'NAME' => "Name",
                'PAGE' => "Page",
                'RAID' => "Raid",
                'MERCENARIES' => "Mercenaries",
                'HISTORY' => "History",
                'MANAGEMENT' => "Management",
                'INVITATIONS' => "Invitations",
                'CREATE_FAMILY' => "Create Family",
                'SHARE_MAFIASOURCE' => "Share ".$route->settings['gamename']."",
                'MEMBERS' => "Members",
                'TOPLIST' => "Toplist", // Use in & out-game
                'LOGOUT' => "Logout",
                'MESSAGE' => "Message",
                'MESSAGES' => "Messages",
                'CLICK_MISSION' => "Click mission",
                'TRAVEL' => "Travel",
                'FRIENDS_BLOCK' => "Friends / block",
                'FRIENDS' => "Friends",
                'NOTIFICATIONS' => "Notifications",
                'NOW' => "Go",
                'END_COPY' => "All rights reserved.",
                'SETTINGS' => "Settings",
                'BOXES' => "Boxes",
                'COMMIT_CRIME' => "Commit Crime",
                'WEAPON' => "Weapon",
                'PIMP_WHORES' => "Pimp Hoes",
                'BOMBARDEMENT' => "Bombardement",
                'CRIME' => "Crime",
                'LATEST' => "Latest",
                'USERNAME' => "Username",
                'USER' => "User",
                'WRONG_CAPTCHA' => "You've entered the wrong security code!", // Use in & out-game
                'INVALID_ACTION' => "You've selected an invalid action!",
                'CANNOT_COMMIT_ACTION_SELF' => "You can't take that action on yourself.",
                'INVALID_SECURITY_TOKEN' => "Invalid security token, please refresh the page (F5) and try again.", // Use in & out-game
                'WAITING_TIME_NOT_PASSED' => "The waiting time hasn't passed yet!",
                'NOT_ENOUGH_MONEY_BANK' => "You don't have enough money on your bank!",
                'NOT_ENOUGH_MONEY_CASH' => "You don't have enough cash money!",
                'PLAYER_DOESNT_EXIST' => "This player doesn't exist!",
                'BETWEEN_100_AND_999M' => "Please choose an amount between $100 and $999,999,999.",
                'BETWEEN_1_AND_999M' => "Please choose an amount between $1 and $999,999,999.",
                'BETWEEN_0_AND_999M' => "Please choose an amount between $0 and $999,999,999.",
                'MESSAGE_UNDER_75_CHARS' => "Please write a message with less than 75 chatacters.",
                'PLACE_MESSAGE' => "Place Message",
                'TYPE_A_MESSAGE' => "Type a message",
                'POST_SUCCESS' => "Your message was added.",
                'MONEY' => "Money",
                'BULLETS' => "Bullets",
                'HELPSYSTEM_FOR' => "Helpsystem for",
                'NO_CONTENT_YET' => "There's no content available yet, please try again later.",
                'WRITE_PLAYERNAME' => "Write a player name",
                'SENDER' => "Sender",
                'RECEIVER' => "Receiver",
                'CLOSE' => "Close",
                'SAVE' => "Save",
                'NONE' => "None", // Use in & out-game
                'ROUND' => "Round",
                'ACTION' => "Action",
                'PICTURE' => "Image",
                'TRANSFER' => "Transfer",
                'EDIT' => "Edit",
                'SEND' => "Send",
                'SENT' => "Sent",
                'REPORT' => "Report",
                'DELETE' => "Delete",
                'DELETED' => "Deleted",
                'CANCEL' => "Cancel",
                'VEHICLE' => "Vehicle",
                'AIRPLANE' => "Airplane",
                'WITH' => "With",
                'TO' => "To",
                'THIS' => "This",
                'DEZETHIS' => "This", // NL-EN fix
                'THESE' => "These",
                'OWNER' => "Owner",
                'HAS_NO_OWNER_YET' => "Has no owner yet!",
                'HAVE_NO_OWNER_YET' => "Have no owner yet!",
                'IS_THE_OWNER_OF' => "Is the owner of",
                'BUY' => "Buy",
                'BUY_' => "Buy",
                'SELL' => "Sell",
                'FOR' => "For",
                'FROM' => "From",
                'TOTAL' => "Total",
                'TOTALE' => "Total",
                'NUMBER' => "Number",
                'DATE' => "Date",
                'BACK' => "Back",
                'TRAVELING' => "You are currently travelling, please wait <span id='cdTravelTime'>{sec}</span> seconds.",
                'CANT_DO_THAT_IN_PRISON' => "You can't do that while you are in prison.",
                'CANT_DO_THAT_TRAVELLING' => "You can't do that while travelling.",
                'MAKE_A_CHOICE' => "Make a choice",
                'THE_UNITED_STATES' => "The United States",
                'WHORES' => "Hoes",
                'LUCKY_BOXES' => "Lucky Boxes",
                'RANK_POINTS' => "Rank points",
                'AVERAGE' => "Average",
                'DONATE' => "Donate",
                'AMOUNT' => "Amount",
                'AMNT' => "Amount", // Amount fix dutch
                'DONATIONS' => "Donations",
                'LAST' => "Last",
                'DONATION' => "Donation",
                'SINCE' => "Since",
                'BEGINNING' => "Beginning",
                'LOGS' => "Logs",
                'ACCEPT' => "Accept",
                'ACCEPTED' => "Accepted",
                'DENY' => "Deny",
                'DENIED' => "Denied",
                'PENDING' => "Pending",
                'STAKE' => "Stake",
                'PLAY' => "Play",
                'FOUND_CREDITS' => "You found {credits} credits with {action}!",
                'WAITING_TIMES' => "Waiting times",
            );
        }
        return $langs;
    }
    
    public function loginLangs()
    {
        $langs = array(
            'WRONG_USERNAME_OR_PASS' => "You've entered the wrong username or password!",
            'LOGIN_FAILED_WARNING' => "You have {attempts} login attempts left!",
            'TEMPORARILY_IP_BANNED' => "You have no more login attempts left, this can take up to 72 hours.",
            'PRE_TITLE_TXT' => "Login to",
            'FORGOT_PASSWORD' => "Forgot username / password?"
        );
        return $langs;
    }
    
    public function screenshotsLangs()
    {
        $langs = array(
            'MOOD_IMAGES' => "Some game shots",
        );
        return $langs;
    }
    
    public function registerLangs()
    {
        global $route;
        $langs = array(
            'SELECT_TAG_CHOOSE' => "Please choose one..",
            'CARJACKER' => "Carjacker - Steal vehicles more easily",
            'PRISON_BREAKER' => "Prison breaker - Easier to break players out of prison",
            'THIEF' => "Thief - Commit crimes easier",
            'PIMP' => "Pimp - You pimp up to 15% more hoes than usual",
            'BANKER' => "Banker - Get 3% interests on your bank instead of 1%",
            'SMUGGLER' => "Smuggler - You get caught less by the border-patrol",
            'EMAIL' => "Email address",
            'PROFESSION' => "Profession",
            'REGISTER_BTN_PAGE' => "Register!",
            'PRE_TITLE_TXT' => "Register on",
            'ENCRYPTED' => "Stored encrypted",
            'REFRESH' => "Refresh",
            'EMAIL_INFO' => "Fill in a valid email address! You will need this if you forget your password somehow.",
            'USERNAME_INFO' => "Only letters, numbers or a hyphen character(-), start with at least 1 letter. 3-15 characters.",
            'INVALID_USERNAME' => "You've entered an invalid username. Only letters, numbers or a hyphen character(-), start with at least 1 letter. 3-15 characters.",
            'INVALID_EMAIL' => "You've entered an invalid email address!",
            'INVALID_PASS' => "Your password has to be atleast 6 characters long!",
            'PASSES_DONT_MATCH' => "The 2 passwords you've entered are not the same!",
            'INVALID_PROFESSION' => "The profession you selected is not valid!",
            'USERNAME_TAKEN' => "The username you entered is already taken!",
            'EMAIL_TAKEN' => "This email address is already taken!",
            'ALREADY_REGISTERED' => "There is already an account registered using this network!",
            'REGISTERED_SUCCESSFUL' => "Welcome criminal! You successfully registered your account on ".$route->settings['gamename']."!",
            'TERMS_CONDITIONS_INFO' => "With your use of our website, you are automatically expected to agree to our <a href='".$route->getRouteByRouteName('terms-and-conditions')."'><strong>terms and conditions</strong></a>.",
        );
        return $langs;
    }
    
    public function recoverPasswordLangs()
    {
        $registerLangs = $this->registerLangs();
        global $route;
        $langs = array(
            'RECOVER_PASSWORD_FOR' => "Recover password for",
            'RECOVER_PASSWORD_INFO' => "If you have forgotten your username, you can insert your email address using this option to retrieve your username. Recovery keys last 2 hours.",
            'RECOVER_PASSWORD' => "Recover password",
            'OR' => "OR",
            'INVALID_USERNAME' => $registerLangs['INVALID_USERNAME'],
            'INVALID_EMAIL' => $registerLangs['INVALID_EMAIL'],
            'RECOVER_PASSWORD_EMAIL_MESSAGE' => "Dear {username}<br /><br />We received a request to recover your lost password, if this wassn't you than you can ignore this email.<br /><br />To set a new password you can click or copy the following URL in your address bar: <a href='".PROTOCOL.strtolower($route->settings['domain'])."/recover-password/key/{key}'>".PROTOCOL.strtolower($route->settings['domain'])."/recover-password/key/{key}</a><br />After that follow the instructions on your sreen.<br /><br />",
            'RECOVER_PASSWORD_EMAIL_MESSAGE_PRIVATEID' => "A PrivateID is set on your account. If you want to deactivate your PrivateID instead, than you can click or copy the following URL in your address bar: <a href='".PROTOCOL.strtolower($route->settings['domain'])."/recover-password/disable-privateid/{key}'>".PROTOCOL.strtolower($route->settings['domain'])."/recover-password/disable-privateid/{key}</a><br /><br />",
            'RECOVER_PASSWORD_EMAIL_FOOTER' => "If you land on a not found page than the above link is expired. You can make a new request on <a href='".PROTOCOL.strtolower($route->settings['domain'])."/recover-password'>".PROTOCOL.strtolower($route->settings['domain'])."/recover-password</a><br /><br /><br />With kind regards<br />".ucfirst($route->settings['domainBase']),
            'RECOVER_PASSWORD_EMAIL_SUBJECT' => "Recover password on ".$route->settings['gamename'],
            'RECOVER_PASSWORD_REQUEST_SUCCESS' => "We've send an email with further instructions to recover your lost password.<br />Beware! The link we've sent you will expire in 2 hours from now.",
            'NEW' => "New",
            'UPDATE_PASSWORD' => "Update password",
            'RECOVER_PASSWORD_SUCCESS' => "You successfully changed your password.",
            'REFRESH' => $registerLangs['REFRESH'],
            'DEACTIVATE_PRIVATEID_SUCCESS' => $this->settingsLangs()['DEACTIVATE_PRIVATEID_SUCCESS']
        );
        return $langs;
    }
    
    public function changeEmailLangs()
    {
        global $route;
        $langs = array(
            'CHANGE_EMAIL_INFO' => "<strong>Caution!</strong> You're about to change your email address for ".$route->settings['gamename'].", check the information below carefully.",
            'CHANGE_EMAIL_ON' => "Change email on",
            'NEW_EMAIL' => "New email address",
            'CHANGE_EMAIL' => "Change email address",
            'CHANGE_EMAIL_SUCCESS' => "You successfully changed your email address!"
        );
        return $langs;
    }
    
    public function notFoundLangs()
    {
        $langs = array(
            'PAGE_DOESNT_EXIST' => "We're sorry because... The page you requested doens't exist. (anymore)",
            'GO_BACK' => "Go back"
        );
        return $langs;
    }
    
    public function newsLangs()
    {
        $langs = array(
            'POSTED_ON' => "Posted on",
            'NEWS_NEWS' => "News",
            'NEWS_UPDATES' => "Updates"
        );
        return $langs;
    }
    
    public function statusLangs()
    {
        $donationShopLangs = $this->donationShopLangs();
        global $route;
        $langs = array(
            'CLICK_FOR_REFERRAL_INFO' => "Click <strong>here</strong> to see the additional referral information!",
            'REFERRAL_INFO' => "Here you see an overview of your account. The referral link is important. By having friends or family members sign up to ".$route->settings['gamename']." with your link, ".$route->settings['gamename']." will get more players and you can earn money. You get $ 1,000,000 if someone signs up through your link. <s>You also get $ 1,000,000 if a referral buys 100 credits from you. You will receive $ 500,000 if a referral of your referral buys 100 credits. Finally, you get $ 250,000 if a referral from the referral of your referral buys 100 credits. (See example).<br /><br />Player A achieves a referral called Player B and receives a one-time $ 1,000,000.<br />Player B buys 100 credits. Player A receives $ 1,000,000.<br /><br />Player B gets three referrals: players C, D and E.<br />Player C buys 100 credits. Player D buys 500 credits and player E buys 200 credits.<br />Player B now receives $ 8,000,000 (after all, 100 credits are bought 8 times).<br />Player A now receives $ 4,000,000 (because he has Player B as a referral).<br /><br />Players C, D and E get 8 referrals together. These 8 referrals together buy 1700 credits.<br />Players C, D and E will receive a total of $ 17,000,000.<br />Player B now receives $ 8,500,000 and Player A receives $ 4,250,000.<br /><br />So if you have a lot of referrals you can make a lot of money. This will be much, much more if your referrals also have referrals, and the referrals thereof!</s>",
            'STATUSBARS' => "Status Bars",
            'SCORE_HOUR' => "Score points each hour",
            'PROTECTION' => "Protection",
            'EXPERIENCE' => "Experience",
            'WINDOW' => "Window",
            'WARNS' => "Warns",
            'STREET' => "Street",
            'PROTECTED' => "You are protected from attacks until",
            'TAKE_AWAY_PROTECTION' => "Click <strong>here</strong> to drop your protection.",
            'LAST_CHANCE' => "Last chance",
            'EQUIPMENT' => "Equipment",
            'RESIDENCE' => "Residence",
            'COPY' => "Copy",
            'COPIED' => "Copied",
            'HALVING_TIMES' => $donationShopLangs['HALVING_TIMES'],
            'BRIBING_BORDER_PATROL' => $donationShopLangs['BRIBING_BORDER_PATROL']
        );
        return $langs;
    }
    
    public function bankLangs()
    {
        global $route;
        $langs = array(
            'SWISS' => "Swiss",
            'FINANCIAL' => "Financial",
            'BANK_LOGS' => "Bank Logs",
            'BANK_TRANSFER' => "Transfer Money",
            'SUMMARY' => "Summary",
            'WHORES_HOUR' => "Hoes average each hour",
            'POSSESSIONS_TOTAL' => "Possessions (total)",
            'GROUND_HOUR' => "Ground (each hour)",
            'RECEIVED' => "Received",
            'CANNOT_SEND_MONEY_SELF' => "You cannot donate money to yourself.",
            'DONATE_MONEY_TO_USER' => "You successfully donated $&#8203;{amount} to {username} minus {transactionPercent}% transaction costs!",
            'INVALID_ACTION' => "Invalid transaction method selected!",
            'TRANSFER_MONEY_SUCCESS' => "You have transfered $&#8203;{amount} to your {action}!",
            'NOT_ENOUGH_MONEY_SWISS' => "You don't have enough money on your swiss bank!",
            'SWISS_BANK_FULL' => "You can't transfer that much money to your swiss bank!",
            'WITHDRAW_MONEY_FROM_BANK' => "Withdraw money from the bank",
            'STORE_MONEY_IN_BANK' => "Store money on the bank",
            'SWISS_BANK_INFO' => "Money on your swiss bank will stay available after a possible death.",
            'SWISS_TRANSACTION_INFO' => "The bank of ".$route->settings['gamename']." takes 5% transaction costs to deposit money into your swiss bank!",
            'SPACE_VAULT' => "Space vault",
            'SPACE_LEFT' => "Space left",
            'WHORES_STREET' => "Hoes on street",
            'WHORES_WINDOW' => "Hoes behind a window",
            'NIGHTCLUB' => "Nightclub",
            'DAILY' => "Daily",
            'INTEREST' => "Interest",
            'NO_DONATIONS_TO_VIEW' => "No donations to view.",
        );
        return $langs;
    }
    
    public function prisonLangs()
    {
        $langs = array(
            'NO_PRISONERS' => "There are no prisoners in jail at the moment.",
            'TIME_LEFT' => "Time left",
            'BUY_OUT' => "Buy out",
            'BREAK_OUT' => "Break out",
            'USER_NOT_IN_PRISON' => "This user is not in prison (anymore).",
            'CANNOT_BREAK_SELF_OUT' => "You can't break yourself out!",
            'NOT_ENOUGH_CASH_TO_BUY_OUT' => "You don't have enough cash to pay the ransom.",
            'USER_BOUGHT_OUT_PRISON' => "You've bought <strong>{playerName}</strong> out of prison!",
            'USER_BREAK_OUT_OF_PRISON' => "You broke {playerName} out of jail, you also gained some rankpoints!",
            'USER_BREAK_OUT_OF_PRISON_FAIL' => "Your attempt to break {playerName} out of jail failed, you were busted for 3 minutes and the time of your pall got extended for 2 minutes.",
            'NO_BREAK_USER_JAILED' => "You can't break anybody out of prison while your in prison too.",
            'IN_PRISON' => "You are currently in jail you are unable to"
        );
        return $langs;
    }
    
    public function honorPointsLangs()
    {
        $langs = array(
            'EXCHANGE' => "Exchange",
            'EXCHANGE_SINGLE' => "Exchange",
            'SEND_HONOR_POINTS_INFO' => "Sending honor points to other players wil slightly reduce your own ranking in the toplist, unless you can earn back what you've send in a short amount of time.",
            'YOU_HAVE_X_HONOR_POINTS' => "You have <strong><i id='userHonorPoints'>{honorPoints}</i> <span id='userHonorPointsChange'></span></strong> honor points in possession.",
            'BEWARE_EXCHANGE' => "Beware! You will exchange x amount of honor points (in possession) for the selected reward after you push the button right.",
            'NO_EXISTING_REWARD' => "You have selected an invalid reward.",
            'NOT_ENOUGH_HONOR_POINTS' => "You don't have that many honor points.",
            'CANNOT_SEND_HP_TO_SELF' => "You can't send honor points to yourself..",
            'EXCHANGE_HONOR_POINTS_SUCCESS' => "You have exchanged {exchangeAmount} honor points for {exchangedValue} {exchangedWhat}.",
            'SEND_HONOR_POINTS_SUCCESS' => "You have sent {amount} honor points to {username}!",
            'LATEST_10_OBTAINED_HP' => "10 latest received honor points",
            'LATEST_10_LOST_HP' => "10 latest sent honor points",
            'NO_HP_LOGS_TO_VIEW' => "No logs to view at the moment.", // FIXEN! TODO NO_LOGS_TO_VIEW baselang use multiple pages
            'HONOR_POINTS_INFO' => "Nobody can touch your honor, you will keep all your points after death."
        );
        return $langs;
    }
    
    public function travelLangs()
    {
        global $route;
        $langs = array(
            'TRAIN' => "Train",
            'AIRPLANE_INFO' => "Travelling by plane is faster but not always safest while smuggling.",
            'TRAIN_INFO' => "Travelling by train is relatively fast and safe.",
            'BUS_INFO' => "Travelling by bus goes slow but is quite safe while smuggeling.",
            'VEHICLE_INFO' => "Travel with a vehicle, the safest but slowest way for smuggling and only pay for gas.",
            'BORDER_PATROL_INFO' => "In the <a href='".$route->getRouteByRouteName('donation-shop')."'><strong>Donation Shop</strong></a> you can bribe the border patrol.",
            'BOOK_TICKET' => "Book ticket",
            'COSTS' => "Costs",
            'ROUTE_NOT_POSSIBLE' => "Route <strong>not possible</strong> by travel medium, consider taking an airplane.",
            'TRAVEL_VEHICLE_NO_SPACE_GARAGE' => "You don't have any space left in the garage in the state you wish to travel to.",
            'TRAVEL_VEHICLE_NO_GARAGE' => "You don't have a garage in the state you wish to travel to.",
            'TRAVEL_VEHICLE_NO_VEHICLE' => "No vehicle selected to travel with!",
            'INVALID_DESTINATION' => "Invalid destination selected!",
            'CANNOT_TRAVEL_WHEN_IN_CRIME' => "You cannot travel to another state while you're active in an organized fazmily crime.",
            'CANNOT_TRAVEL_WHEN_IN_RAID' => "You cannot travel to another state while you're active in an organized fazmily raid.",
            'CAUGHT_BY_BORDER_PATROL' => "You were caught smuggling goods, you were arrested for 2 minutes and all your contraband was confiscated!",
            'TRAVEL_TO_SUCCESS' => "You are travelling to {state} for $&#8203;{price}, you will arive at your destination in approximately {sec} seconds."
        );
        return $langs;
    }
    
    public function marketLangs()
    {
        $langs = array(
            'ANONYMOUS' => "Anonymous",
            'PRICE' => "Price",
            'PLACE_OR_REQUEST_X_ON_MARKET' => "Place or request {typeName} on the market",
            'NO_ITEMS_IN_MARKET' => "There are no {typeName} for sale at the moment.",
            'NO_REQUESTS_IN_MARKET' => "There are no {typeName} requested at the moment.",
            'ITEMS_SALE_INFO' => "All {typeName} above are being sold by the owner.",
            'ITEMS_REQUEST_INFO' => "All {typeName} above are being bought by the user.",
            'OFFER' => "Offer",
            'WRONG_MARKET_TYPE_SELECTED' => "You have provided an invalid category!",
            'AMOUNT_RANGE_BETWEEN_25_10K' => "Please enter an amount between 25 and 10,000.",
            'PRICE_RANGE_BETWEEN_250K_9.999B' => "Enter a price between $250,000 and $9,999,999,999.",
            'NOT_ENOUGH_AMOUNT_FOR_SALE' => "You don't have that many {typeName} to sell!",
            'MARKET_ITEM_ADD_SUCCESS' => "You've put {typeName} on the market. You will be redirected shortly.",
            'MARKET_ITEM_REQUEST_SUCCESS' => "You have requested {typeName} on the market. You will be redirected shortly.",
            'MARKET_ITEM_DOESNT_EXIST' => "You've selected an invalid item.",
            'CANT_BUY_SELL_OWN_MARKET_ITEM' => "You can't interact with your own market item.",
            'BOUGHT_MARKET_ITEM_SUCCESS' => "You've <strong>bought</strong> {amount} {typeName} for $&#8203;{price}.",
            'ACCEPT_MARKET_ITEM_SUCCESS' => "You've <strong>sold</strong> {amount} {typeName} for $&#8203;{price}.",
            'MARKET_DEATH_INFO' => "Only placed credits and/or honor points for sale will stay on the market after you die.",
            'SUPPLY_AND_DEMAND_INFO' => "What is put on the market can't be taken away without dying."
        );
        return $langs;
    }
    
    public function stockExchangeLangs()
    {
        global $route;
        $langs = array(
            'EXCHANGE' => "Exchange",
            'BUSINESS' => "Business",
            'DIFFERENCE' => "Difference",
            'DIFF' => "Diff",
            'HIGH' => "High",
            'LOW' => "Low",
            'PRICE' => $this->marketLangs()['PRICE'],
            'EACH' => "Each",
            'CURRENT' => "Current",
            'CLOSING' => "Closing",
            'HIGHEST_DAY' => "Highest day",
            'LOWEST_DAY' => "Lowest day",
            'DAY' => "Dag",
            'PURCHASE' => "Purchase",
            'PROFIT' => "Profit",
            'EXCHANGE_DORMANT' => "The stock exchange is curently dormant until 6:00 AM.",
            'DONT_OWN_STOCKS' => "You curently have no stocks in your portfolio.",
            'BUSINESS_STOCKS_INFO' => "You have <strong><span id='stockAmount'>{stocks}</span><span id='stockAmountChange'></span></strong> stocks in possession of {business}.",
            'BUSINESS_DONATOR_INFO' => "You can own 1,000,000 stocks in total. Become <a href=".$route->getRouteByRouteName('donation-shop')."><strong>Donator</strong></a> to raise your limit.",
            'BUSINESS_VIP_INFO' => "You can own 2,500,000 stocks in total. Become <a href=".$route->getRouteByRouteName('donation-shop')."><strong>VIP</strong></a> to raise your limit.",
            'AVERAGE_DAY_PRICE_15_DAYS' => "Average day price of the past 15 days",
            'NOT_DEVISABLE_BY_100' => "Make sure the amount of stocks is devisable by 100.",
            'CANNOT_BUY_OVER_LIMIT' => "You cannot own that many stocks.",
            'DONT_OWN_THAT_MANY' => "You don't own that many stocks of this business.",
            'AMOUNT_RANGE_BETWEEN_100_AND_5M' => "Please enter an amount between 100 and 5,000,000.",
            'BOUGHT_STOCKS_SUCCESS' => "You've bought {stocks} stocks from {business} for $&#8203;{price}.",
            'SOLD_STOCKS_SUCCESS' => "{stocks} stocks of {business} were sold for $&#8203;{price}.",
        );
        return $langs;
    }
    
    public function equipmentStoresLangs()
    {
        $langs = array(
            'WEAPONS' => ucfirst($this->langMap['WEAPON']."s"),
            'PROTECTION' => $this->statusLangs()['PROTECTION'],
            'AIRPLANES' => ucfirst($this->langMap['AIRPLANE']."s"),
            'PRICE' => $this->marketLangs()['PRICE'],
            'AVERAGE_WEAPON_EXP_TRAIN' => "Average weapon- experience and -training",
            'IN_POSSESSION' => $this->smugglingLangs()['IN_POSSESSION'],
            'NOT_ENOUGH_WEAPON_EXP_TRAIN' => "Not enough weapon- experience and/or training",
            'DAMAGE' => "Damage",
            'DAMAGE_PER_HIT' => "Damage per hit",
            'BOMBING_POWER' => "Bombing power",
            'EQUIP' => "Equip",
            'EQUIPPED' => "Equipped",
            'EQUIPMENT_DOESNT_EXIST' => "This equipment doesn't exist.",
            'ALREADY_OWN_EQUIPMENT' => "You already own this piece of equipment.",
            'BOUGHT_EQUIPMENT_SUCCESS' => "You bought a {name} for $&#8203;{price} and equipped it immediately!",
            'DONT_OWN_EQUIPMENT' => "You don't own this piece of equipment.",
            'SOLD_EQUIPMENT_SUCCESS' => "You sold a {name} for $&#8203;{price}!",
            'EQUIP_EQUIPMENT_SUCCESS' => "You've equipped a {name}.",
        );
        return $langs;
    }
    
    public function estateAgencyLangs()
    {
        $esLangs = $this->equipmentStoresLangs();
        $langs = array(
            'PRICE' => $this->marketLangs()['PRICE'],
            'IN_POSSESSION' => $this->smugglingLangs()['IN_POSSESSION'],
            'EQUIP' => $esLangs['EQUIP'],
            'EQUIPPED' => $esLangs['EQUIPPED'],
            'DEFENCE' => "Defence",
            'RESIDENCE_DOESNT_EXIST' => "This house doesn't exist.",
            'ALREADY_OWN_RESIDENCE' => "You already own this house.",
            'BOUGHT_RESIDENCE_SUCCESS' => "You bought a {name} for $&#8203;{price} and equipped it immediately!",
            'DONT_OWN_RESIDENCE' => "You don't own this house.",
            'SOLD_RESIDENCE_SUCCESS' => "You sold your {name} for $&#8203;{price}!",
            'EQUIP_RESIDENCE_SUCCESS' => "You've equipped your {name} as current house.",
        );
        return $langs;
    }
    
    public function bulletFactoriesLangs()
    {
        $langs = array(
            'BULLET_FACTORY' => "Bullet factory",
            'THIS_BF_IS_CURRENTLY' => "This bullet factory is currently",
            'PRODUCING' => "Producing",
            'DORMANT' => "Dormant",
            'BULLETS_FOR_SALE' => "Bullets for sale",
            'PRICE_EACH_BULLET' => "Price each bullet",
            'ATM_NO_MORE_BULLETS_FOR_SALE_IN_THIS' => "At the moment there are no more bullets for sale in this",
            'PRODUCTION' => "Production",
            'PRICE' => $this->marketLangs()['PRICE'],
            'BETWEEN_1_AND_9M_BULLETS' => "You need to buy between 1 and 9,999,999 bullets!",
            'NOT_THAT_MANY_BULLETS_IN_FACTORY' => "This bullet factory doesn't have that many bullets left.",
            'BOUGHT_BULLETS_SUCCESS' => "You bought {bullets} and payed a total of $&#8203;{price}."
        );
        return $langs;
    }
    
    public function hitlistLangs()
    {
        $langs = array(
            'ANONYMOUS' => $this->marketLangs()['ANONYMOUS'],
            'PRIZE' => $this->luckyboxLangs()['PRIZE'],
            'COSTS_ANONYMOUS' => "Costs 30% extra",
            'REASON' => "Reason",
            'ORDER' => "Request order",
            'ORDERER' => "Orderer",
            'BUY_OUT' => $this->prisonLangs()['BUY_OUT'],
            'NO_ORDERS' => "There are no open orders on the hitlist right now.",
            'PRIZE_ATLEAST_10K' => "The prize needs to be atleast $10,000!",
            'PLAYER_ALREADY_ON_HITLIST' => "This player is already present on the hitlist!",
            'PLAYER_ALREADY_DEAD' => $this->murderLangs()['PLAYER_ALREADY_DEAD'],
            'CANNOT_ORDER_SELF_HITLIST' => "You can't put yourself on the hitlist!",
            'ORDER_HITLIST_RECORD_SUCCESS' => "You've put {user} on the hitlist for $&#8203;{price}.",
            'PLAYER_NOT_ON_HITLIST' => "This player is not present on the hitlist!",
            'CANNOT_BUY_OUT_SELF_HITLIST' => "You can't buy yourself out of the hitlist!",
            'BUY_OUT_HITLIST_RECORD_SUCCESS' => "You bought {user} out of the hitlist for $&#8203;{price}."
        );
        return $langs;
    }
    
    public function murderLangs()
    {
        global $route;
        $langs = array(
            'MERCENARIES' => "Mercenaries",
            'IN_POSSESSION' => $this->smugglingLangs()['IN_POSSESSION'],
            'CURRENT' => "Current",
            'CANT_MURDER_WITH_PROTECTION' => "You can't attack this player with protection. Go to the <a href='".$route->getRouteByRouteName('status')."'><strong>Status</strong></a> page to remove your protection.",
            'FIRE_1_BULLET_MIN' => "You need to fire atleast 1 bullet!",
            'DONT_OWN_THAT_MANY_BULLETS' => "You don't have that many bullets in possession!",
            'INVALID_AMOUNT_OF_BULLETS' => "You can fire 9,999,999,999 bullets maximum!",
            'CANNOT_MURDER_WITH_CASH_OR_BANK_IN_MIN' => "You cannot attack anyone when your cash or bank is below zero!",
            'ALREADY_ATTACKED_PLAYER_LAST_10MIN' => "You already attacked this player in the past 10 minutes!",
            'CANT_MURDER_PLAYER_IN_ALLIANCE_WITH_FAMILY' => "You cannot attack players from a family in alliance!",
            'NOT_IN_SAME_CITY' => "You're not in the same city as your victim!",
            'CANT_MURDER_PLAYER_WITH_PROTECTION' => "You can't attack this player under protection!",
            'CANT_MURDER_TEAM_MEMBER' => "You can't attack this team member!",
            'CANNOT_MURDER_PLAYER_WITH_CASH_OR_BANK_IN_MIN' => "You can't attack someone whose cash or bank is below zero!",
            'CANT_MURDER_PLAYER_INSIDE_FAMILY' => "You can't attack this family member!",
            'CANNOT_ATTACK' => "You cannot attack this player! View <a href='".$route->getRouteByRouteName('ranks-score')."'><strong>Ranks & Score</strong></a> to see who you can attack.",
            'MURDER_PLAYER_SUCCESS_WEAPON_EXP' => "You received <strong>{exp}%</strong> weapon experience.<br /><br />",
            'MURDER_PLAYER_SUCCESS_HEADSHOT' => "<strong>You made a <font color=red>H</font><font color=black>E</font><font color=red>A</font><font color=black>D</font><font color=red>S</font><font color=black>H</font><font color=red>O</font><font color=black>T</font> on {victim}</strong><br />This murder only costed you 1 bullet and you received an additional <strong>$500,000</strong>!<br />",
            'MURDER_PLAYER_ON_HITLIST_SUCCESS' => "{user} was listed on the hitlist. Because of this you received an additional $&#8203;{prize}!",
            'TAKE_OVER_POSSESSION_TOOK_OVER' => "<div>✓You took over a {possessionName}.</div>",
            'TAKE_OVER_POSSESSION_STATUS_ERROR' => "<div>✘You didn't take over a {possessionName} because you have protection it was put for sale.</div>",
            'TAKE_OVER_POSSESSION_SELF_ERROR' => "<div>✘You didn't take over a {possessionName} because you already own a {possessionName} it was put for sale.</div>",
            'TAKE_OVER_POSSESSION_SELF_COUNTRY_ERROR' => "<div>✘You didn't take over a {possessionName} because you already own a country possession it was put for sale.</div>",
            'TAKE_OVER_POSSESSION_FAMILY_ERROR' => "<div>✘You didn't take over a {possessionName} because your family already owns the maximum amount it was put for sale.</div>",
            'TAKE_OVER_POSSESSION_FAMILY_COUNTRY_ERROR' => "<div>✘You didn't take over a {possessionName} because your family already owns the maximum amount of country possessions it was put for sale.</div>",
            'MURDER_SUCCESS_DIED_VICTIM_SURVIVED' => "You shot <strong>{victim}</strong>, he <strong>survived</strong> your shots.<br />You died bu his last shot!<br><strong>{victim}</strong> has also stolen <strong>$&#8203;{stolenMoney}</strong>!",
            'MURDER_SUCCESS_BOTH_DIED' => "You shot <strong>{victim}</strong>, he <strong>died</strong> by your shots.<br />You also <strong>died</strong> by his last shots!",
            'MURDER_SUCCESS_KILLED_VICTIM' => "You shot <strong>{victim}</strong>, he <strong>died</strong> by your shots.<br />You also stole <strong>$&#8203;{stolenMoney}</strong>!",
            'MURDER_SUCCESS_BOTH_SURVIVED_VICTIM_STOLE' => "You shot <strong>{victim}</strong>, he <strong>survived</strong> your shots.<br />He also stole <strong>$&#8203;{stolenMoney}</strong>!",
            'MURDER_SUCCESS_BOTH_SURVIVED_ATTACKER_STOLE' => "You shot <strong>{victim}</strong>, he <strong>survived</strong> your shots.<br />He also stole <strong>$&#8203;{stolenMoney}</strong>!",
            'EXPERIENCE' => $this->statusLangs()['EXPERIENCE'],
            'ALREADY_100_WEAPON_TRAINING' => "You're already at 100% weapon training, gain experience by attacking players.",
            'TRAIN_WEAPON_TRAINING_SUCCESS' => "You've trained your weapon skills and gained {percent}% for your training bar.",
            'ALL_YOUR' => "All your",
            'DOUBLE_OF_ATTACKER' => "Double the amount of your attacker",
            'SAME_AS_ATTACKER' => "The same as your attacker",
            'HALF_OF_ATTACKER' => "Half the amount of your attacker",
            'INVALID_BACKFRITE_TYPE' => "You selected an invalid backfire type!",
            'BACKFIRE_PERCENT_BETWEEN_0_AND_100' => "The backfire percentage must range between 0% and 100%!",
            'BACKFIRE_NUMBER_HIGHER_OR_0' => "The amount of backfire bullets must be higher or equal to O!",
            'BACKFIRE_SETTINGS_SAME_AS_CURRENT' => "These backfire settings already apply!",
            'SET_BACKFIRE_SUCCESS' => "You've saved your backfire settings.",
            'VICTIM' => "Victim",
            'TIME_LEFT' => $this->prisonLangs()['TIME_LEFT'],
            'NOW_IN' => "Now in",
            'LAST_SEEN_IN' => "Last seen in",
            'SEARCHING' => "Searching",
            'NO_DETECTIVES' => "You didn't hire any detectives at the moment.",
            'HIRE_DETECTIVE' => "Hire detective",
            'TIME' => "Time",
            'SHADOW' => $this->ranksScoreLangs()['SHADOW'],
            'SHADOW_INFO' => "Keep following after find",
            'HOUR' => $this->familyPropertiesLangs()['HOUR'],
            'CANT_HIRE_WITH_PROTECTION' => "You can't hire a detective with protection. Go to the <a href='".$route->getRouteByRouteName('status')."'><strong>Status</strong></a> page to remove your protection.",
            'INVALID_TIME' => "You selected an invalid time!",
            'ALL_DETECTIVES_IN_USE' => "All your detectives are already in use!",
            'PLAYER_ALREADY_IN_SEARCHLIST' => "You already have a detective that is searching for this player.",
            'PLAYER_ALREADY_DEAD' => "This player is already dead.",
            'CANNOT_ATTACK_CANNOT_HIRE' => "You cannot hire a detective for this player because you cannot attack him! View <a href='".$route->getRouteByRouteName('ranks-score')."'><strong>Ranks & Score</strong></a> to see who you can attack.",
            'HIRE_DETECTIVE_SUCCESS' => "You've hired a detective that's on the lookout for {victim}.",
        );
        return $langs;
    }
    
    public function murderLogLangs()
    {
        $langs = array(
            'ATTACK' => "Attack",
            'ATTACKED_BY' => "Attacked by",
            'RESULT' => "Result",
            'YOU' => "You",
            'YOUJOU' => "You",
            'YOUR' => "Your",
            'HE' => "He",
            'HIS' => "His",
            'YALL' => "You",
            'SHOT' => "shot",
            'SHOTS' => "shots",
            'BOTH_DIED_BY_THE' => "both died by the",
            'SURVIVED_THE' => "survived the",
            'DIED_BY' => "died by",
            'NO_ATTACK_LOGS' => "There are no attack logs found (on this page) yet.",
            'NO_ATTACKED_LOGS' => "There are no attacked by logs (on this page) found.",
            'THEY' => "They"
        );
        return $langs;
    }
    
    public function hospitalLangs()
    {
        $langs = array(
            'HEAL' => "Heal",
            'NO_PLAYER_NEEDS_HEALING' => "There are no wounded players which you can heal!",
            'MEMBER_NOT_WOUNDED' => "This player isn't wounded!",
            'CANNOT_HEAL_DEAD_MEMBER' => "You cannot heal dead players.",
            'HEAL_MEMBER_SUCCESS' => "{member} is completely healed! You have paid $&#8203;{costs} hospital costs.",
        );
        return $langs;
    }
    
    public function redLightDistrictLangs()
    {
        $langs = array(
            'BUY_WINDOWS' => "Buy Windows",
            'WINDOWS_AVAILABLE' => "Windows available",
            'BUY_WINDOWS_INFO' => "Hoes behind a window earn up to $3 more each hour than hoes on the street. Add street hoes to your family brothel so they won't get lost after you die.",
            'USER_WHORES_INFO' => "You have <strong class='gray'><span id='totalWhores'>{totalWhores}</span><span id='totalWhoresChange'></span> hoes</strong>, <strong class='gray'><span id='whoresStreet'>{streetWhores}</span><span id='whoresStreetChange'></span> on the streets</strong> and <strong class='gray'><span id='whoresWindow'>{windowWhores}</span><span id='whoresWindowChange'></span> behind a window</strong>.",
            'TOTAL_PROFITS_FROM_WHORES' => "Profits by hoes",
            'TOTAL_PIMPS_COMMITTED' => "Times pimped",
            'TOTAL_WHORES_PIMPED' => "Total hoes pimped",
            'PIMP_WHORES_INFO' => "All of your hoes will disappear after death. You will earn bucks for every hoe each hour, the more hoes you've pimped the more cash you will receive.",
            'PIMP_WHORES_SELF_SUCCESS' => "You've pimped {amount} hoes, you immediately put them to work.",
            'CANNOT_PIMP_FOR_SELF' => "You can't pimp hoes for yourself through your own profile.",
            'PIMP_WHORES_FOR_OTHER_SUCCESS' => "You've pimped {amount} hoes for {user}, he immediately put them to work.",
            'SUMMARY' => "Summary",
            'BEHAND_A_WINDOW' => "Behind a window",
            'PRICE_EACH_WINDOW' => "Price each window",
            'WHORES_AVAILABLE' => "Hoes available",
            'WINDOWS' => "Windows",
            'NOT_ENOUGH_WINDOWS_LEFT' => "There not enough windows left in this red light district.",
            'NOT_ENOUGH_STREET_WHORES' => "You don't have that many street hoes.",
            'INVALID_AMOUNT_WINDOWS' => "You've entered an invalid amount of windows.",
            'BUY_WINDOWS_SUCCESS' => "You've bought {amount} windows in this red light district! Your hoes are put to work immediately.",
            'TAKE_AWAY' => "Take away",
            'INVALID_STATE_SELECTED' => "You've selected an invalid state to take away hoes from windows.",
            'NOT_THAT_MUCH_WHORES_WINDOW' => "You don't have that many hoes behind these windows!",
            'TAKE_AWAY_WINDOWS_SUCCESS' => "You took away {amount} hoes behind windows in {state}!",
            'NO_WHORE_BEHIND_WINDOWS' => "You have no hoes behind windows."
        );
        return $langs;
    }
    
    public function gymLangs()
    {
        $langs = array(
            'TRAIN_PUSH_UP' => "Push up 25x",
            'TRAIN_CYCLE' => "Cycle 1.25 mi.",
            'TRAIN_BENCH_PRESS' => "Bench press 55 lb.",
            'TRAIN_RUN' => "Run 3.1 mi.",
            'FAST_ACTION_ON' => "Fast action on",
            'PUSH_UPS' => "Push ups",
            'CYCLING' => "Cycling",
            'BENCH_PRESSING' => "Bench pressing",
            'RUNNING' => "Running",
            'TRAIN_INFO' => "Train up to 100% so you can keep training faster.",
            'POWER' => "Power",
            'PROFIT_LOSS_RATIO' => "Profit/Loss ratio",
            'TOTAL_PROFIT' => "Total profit",
            'SCORE_POINTS_EARNED' => "Score points earned",
            'COMPETITIONS' => "Competitions",
            'NO_COMPETITIONS_ATM' => "There are no open competitions at this moment.",
            'COMPETITION' => "Competition",
            'ARM_WRESTLING' => "Arm wrestling",
            'SPRINT' => "Sprint 0.3 mi.",
            'TUG_OF_WAR' => "Tug of war",
            'TRIATLON' => "1.25 mi. triatlon",
            'WRESTLE' => "Wrestle",
            'CHALLENGE' => "Challenge",
            'STAKE_BETWEEN_50_5M' => "Stake between $50 and $5,000,000",
            'GYM_INFO' => "Your gym training, power and cardio will reset after death they will also start to decrease slowly if you haven't had a workout for over 48 hours resulting in a possible timeloss for each workout.",
            'GYM_TRAINING_DOESNT_EXIST' => "This training doesn't exist.",
            'GYM_FAST_ACTION_CHANGE_SUCCESS' => "You've set your fast action training to {type}.",
            'TRAIN_GYM_BOTH_STATS_SUCCESS' => "You started {type} and gained {power} power and {cardio} cardio.",
            'TRAIN_GYM_POWER_SUCCESS' => "You started {type} and gained {power} power.",
            'TRAIN_GYM_CARDIO_SUCCESS' => "You started {type} and gained {cardio} cardio.",
            'LOCATION' => $this->possessionsLangs()['LOCATION'],
            'COMPETITION_DOESNT_EXIST' => "You've selected an invalid competition.",
            'COMPETITION_ALREADY_STARTED_COMPETITION' => "You already started a competition, please wait until a player challenges you.",
            'COMPETITION_CREATE_COMPETITION_SUCCESS' => "You started a {competition} competition with a $&#8203;{stake} stake in {location}, now wait until a player challenges your prestations in the same gym.",
            'COMPETITION_IN_OTHER_CITY' => "This competition is hosted in {location}, please travel there if you want to participate.",
            'COMPETITION_CANNOT_CHALLENGE_SELF' => "You cannot challenge yourself on your own competition.",
            'COMPETITION_CHALLENGE_WIN' => "You challanged this player and won! You've earned a $&#8203;{profits} stake and {scorePoints} score points!",
            'COMPETITION_CHALLENGE_LOSE' => "The player you've challenged has beat you! You've lost a $&#8203;{stake} stake but earned {scorePoints} score points!",
            'COMPETITION_CHALLENGE_DRAW' => "The competition was tough and ended in a draw! You didn't lose your stake and earned {scorePoints} score points!"
        );
        return $langs;
    }
    
    public function groundLangs()
    {
        $langs = array(
            'VIEW' => "View",
            'FROM' => "From",
            'OF' => $this->garageLangs()['OF'],
            'NO_ONE' => "No one",
            'IS_THE_OWNER_OF_THIS' => $this->langMap['IS_THE_OWNER_OF']. " " . $this->langMap['DEZETHIS'],
            'LOCATION' => $this->possessionsLangs()['LOCATION'],
            'GROUND' => "Ground",
            'BOMB_GROUND' => "Bomb ground",
            'BOMBS' => "Bombs",
            'BOMB' => "Bomb",
            'BOMBING_INFO' => "Max. 35, $10,000 each bomb.",
            'BUILDINGS' => "Buildings",
            'PRICE' => $this->marketLangs()['PRICE'],
            'PROFIT' => $this->stockExchangeLangs()['PROFIT'] . " / Hour",
            'GROUND_OWNER_INFO' => "Buy buildings to earn money every hour! Upgrade your buildings capacity to earn up to 25% more each hour.",
            'GROUND_ALREADY_OWNED' => "This ground area is in another players possession.",
            'USER_TRAVEL_SAME_STATE_AS_MAP' => "You need to travel to a city in {state} to be able to buy this ground.",
            'ALREADY_OWN_MAX_GROUND' => "You already own {limit} ground areas across all states!",
            'BUY_GROUND_SUCCESS' => "You bought this ground area in {state} for $100,000!",
            'USER_BUY_BUILDING_TRAVEL_SAME_STATE_AS_MAP' => "You need to travel to a city in {state} to be able to buy this building on this ground.",
            'USER_UPGRADE_BUILDING_TRAVEL_SAME_STATE_AS_MAP' => "You need to travel to a city in {state} to be able to upgrade this building further.",
            'DONT_OWN_THIS_GROUND' => "You don't own this ground area!",
            'ALREADY_OWN_THIS_BUILDING' => "You already own this building on this area.",
            'ALREADY_UPGRADED_THIS_BUILDING' => "This building has already reached it's max capacity.",
            'BUY_GROUND_BUILDING_SUCCESS' => "You bought a {building} on your ground area in {state}. You paid $&#8203;{price} for this building.",
            'UPGRADE_GROUND_BUILDING_SUCCESS' => "You upgraded your {building} on your ground area in {state}. You paid $&#8203;{price}.",
            'BOMBS_BETWEEN_1_AND_35' => "You can only carry between 1 and 35 bombs.",
            'DONT_OWN_AIRPLANE' => "You don't own an airplane, you can buy one in the <a href='/game/equipment-stores/airplanes'><strong>equipment stores</strong></a>",
            'CANNOT_BOMB_OWN_GROUND' => "You cannot bomb your own ground area!",
            'USER_BOOMB_TRAVEL_SAME_STATE_AS_MAP' => "You need to travel to a city in {state} to be able to bomb this ground.",
            'BOMB_GROUND_SUCCESS' => "You've conquered this ground area in {state}! You paid $&#8203;{price} in bomb costs and earned 2 rankpoints.",
            'BOMB_GROUND_FAILED' => "Your airstrike failed to conquer this ground area! You paid $&#8203;{price} in bomb costs."
        );
        return $langs;
    }
    
    public function missionsLangs()
    {
        $langs = array(
            'PRIZE' => $this->luckyboxLangs()['PRIZE'],
            'EACH' => $this->stockExchangeLangs()['EACH'],
            'MISSIONS_INFO' => "Fully complete missions to unlock their badge on your player profile.",
            "PUBLIC" => "Public",
            "MISSION" => "Mission",
            "TIME_LEFT" => "Time left",
            "PAYOUT" => "Payout",
            "PROGRESS" => "Progress",
        );
        return $langs;
    }
    
    public function dailyChallengesLangs()
    {
        $langs = array(
            'PRIZE' => $this->luckyboxLangs()['PRIZE'],
            'EXPERIENCE' => $this->statusLangs()['EXPERIENCE'],
            'DAILY_INFO' => "Complete all challenges to receive your <strong>{luckies}</strong> bonus lucky box(es) for today.",
        );
        return $langs;
    }
    
    public function possessionsLangs()
    {
        $rldLangs = $this->redLightDistrictLangs();
        $famPropLangs = $this->familyPropertiesLangs();
        global $route;
        $langs = array(
            'COUNTRY_POSSESSIONS' => "Country possessions",
            'STATE_POSSESSIONS' => "State possessions",
            'CITY_POSSESSIONS' => "City possessions",
            'TOTAL_REVENUE' => "Total revenue",
            'MANAGE' => "Manage",
            'DROP' => "Drop",
            'LOCATION' => "Location",
            'EVERYWHERE_IN_THE_US' => "Everywhere",
            'VIEW_FROM' => "View possessions from",
            'UNKNOWN_POSSESSION' => "This possession doesn't exist!",
            'CANT_BUY_FROM_DIFFERENT_STATE' => "You can't buy this possession while you're in another state.",
            'CANT_BUY_FROM_DIFFERENT_CITY' => "You can't buy this possession while you're in another city.",
            'CANT_BUY_WITH_PROTECTION' => "You can't buy a possession with protection. <a href='".$route->getRouteByRouteName('status')."'><strong>Click here</strong></a> to go to the status page and remove your protection.",
            'CANT_RECEIVE_WITH_PROTECTION' => "You can't receive a possession with protection. <a href='".$route->getRouteByRouteName('status')."'><strong>Click here</strong></a> to go to the status page and remove your protection.",
            'POSSESSION_HAS_OWNER_ALREADY' => "This possession is already owned by another player.",
            'ALREADY_OWN_SAME_POSSESSION' => "You already own this possession somewhere else, you can drop it from every location on the possessions page.",
            'RECEIVER_ALREADY_OWN_SAME_POSSESSION' => "This player already owns this possession somewhere else.",
            'FAMILY_MAX_POSSESSION' => "Your family already owns the maximum amount of this possession.",
            'RECEIVER_FAMILY_MAX_POSSESSION' => "This player's family already owns the maximum amount of this possession.",
            'FAMILY_MAX_COUNTRY_POSSESSION' => "Your family already owns a country possession.",
            'RECEIVER_FAMILY_MAX_COUNTRY_POSSESSION' => "This player's family already owns a country possession.",
            'USER_ALREADY_OWN_COUNTRY_POSSESSION' => "You already own a country possession.",
            'RECEIVER_ALREADY_OWN_COUNTRY_POSSESSION' => "This player already owns a country possession.",
            'BOUGHT_POSSESSION_SUCCESS' => "You paid {price} to the notary and you've become the new owner of {pName} in {location}!",
            'DROP_POSSESS_CONFIRM' => "Are you sure you want to drop this possession?<br /><br /><form id='dropPossessionConfirm' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('drop-possession') ."' data-response='#dropPossessionResponse{id}'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='id' value='{id}'/><input type='submit' class='btn button alert-btn' name='drop-possess-confirm' value='Drop'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'DROP_POSSESS_SUCCESS' => "You have dropped your {pName}, it has been put back on sale.",
            'ALREADY_TRANSFERED_TO_RECEIVER' => "You already transfered this possession to this player.",
            'TRANSFER_POSSESS_REQUEST_SUCCESS' => "You've sent your {pName} in {location} to {user}, he has 48h time to handle this request.",
            'SENDER_DOESNT_OWN_POSSESSION' => "{sender} can't send this possession anymore. This request is now deleted.",
            'ACCEPT_TRANSFER_POSSESS_SUCCESS' => "You've accepted the {pName} in {location} from {sender}, you're now the new owner of this possession.",
            'DENY_TRANSFER_POSSESS_SUCCESS' => "You've denied the {pName} in {location} from {sender}.",
            'PRICE_2500_IF_OVER_10M_BULLETS' => "The price can only be set at $2,500 when the factory has more than 10,000,000 bullets.",
            'PRICE_ALREADY_SET' => "This price is already set!",
            'BETWEEN_200_AND_2500_BULLET_PRICE' => "The price each bullet must range between $200 and $2,500!",
            'CHANGE_BULLET_PRICE_SUCCESS' => "You've set the price for each bullet to $&#8203;{price}.",
            'BETWEEN_50_AND_300_WINDOW_PRICE' => "The price each window must range between $50 and $300!!",
            'CHANGE_WINDOW_PRICE_SUCCESS' => "You've set the price for each window to $&#8203;{price}.",
            'BETWEEN_1K_AND_500K_STAKE' => "The maximum stake must range between $1,000 and  $500,000!",
            'BETWEEN_1K_AND_500K_MASS_MESSAGE' => "The mass message price must range between $1,000 and  $500,000!",
            'CHANGE_STAKE_SUCCESS' => "You've set the maximum stake to $&#8203;{stake}.",
            'CHANGE_MASS_MESSAGE_PRICE_SUCCESS' => "You've set the price for a mass message to $&#8203;{price}.",
            'INVALID_PRODUCTION' => $famPropLangs['INVALID_PRODUCTION'],
            'SET_BF_PRODUCTION_SUCCESS' => $famPropLangs['SET_BF_PRODUCTION_SUCCESS'],
            'BUY_WINDOWS_SUCCESS' => "You bought 10,000 windows for $1,000,000.",
            'OVERVIEW' => "Overview",
            'PROFIT_HOUR' => $this->stockExchangeLangs()['PROFIT'] . " this hour",
            'BULLET' => "Bullet",
            'PRICE' => $this->marketLangs()['PRICE'],
            'PRODUCTION' => $this->bulletFactoriesLangs()['PRODUCTION'],
            'WINDOWS' => $rldLangs['WINDOWS'],
            'BUY_WINDOWS' => $rldLangs['BUY_WINDOWS'],
            'PRICE_EACH_WINDOW' => $rldLangs['PRICE_EACH_WINDOW'],
            'MAXIMUM_STAKE' => "Maximum stake",
            'CASINO_INFO' => "You have <strong>$&#8203;<span id='casinoStakeAmount'>{money}</span><span id='casinoStakeAmountChange'></span></strong> available to gamble...<br />The maximum stake is <strong>$&#8203;{max}</strong>",
            'STAKE_BETWEEN_1_AND_MAX' => "Stake between $1 and $&#8203;{max}.",
            'CANNOT_PLAY_IN_OWN_CASINO' => "You cannot play in your own {casinoName}!",
            'PLAY_CASINO_BROKE_TOOK_OVER' => "You played this {casinoName} broke and you've became the new owner.",
            'PLAY_CASINO_BROKE_STATUS_ERROR' => "You played this {casinoName} broke but because you have protection it was put for sale.",
            'PLAY_CASINO_BROKE_SELF_ERROR' => "You played this {casinoName} broke but because you already own a {casinoName} it was put for sale.",
            'PLAY_CASINO_BROKE_FAMILY_ERROR' => "You played this {casinoName} broke but because your family already owns the maximum amount it was put for sale.",
        );
        return $langs;
    }
    
    public function donationShopLangs()
    {
        global $route;
        $registerLangs = $this->registerLangs();
        $langs = array(
            'CREDITS_INFO' => "You have <strong>{credits} credits</strong> in possession. <!-- <a href='#'><i class='donator'>Donate!</i></a> -->",
            'DONATION_SHOP_INFO' => "Al status benefits can be found on the <a href='javascript:void(0);' class='ajaxTab help' data-tab='help'><img src='/foto/web/public/images/icons/help.png' class='icon' alt='Help'/> Help page.</a>",
            'ACHIEVED_HIGHEST_STATUS' => "You achieved the highest status in ".$route->settings['gamename']."!",
            'PROFIT_DISCOUNT' => "Profit from a discount if you immediately become Gold Member.",
            'FAMILY_IS_VIP' => "Your family has a VIP status!",
            'NOT_ENOUGH_CREDITS' => "You don't have enough credits for this purchase.",
            'USER_ALREADY_HAS_STATUS' => "You've already bought this status.",
            'FAMILY_ALREADY_VIP' => "Your family is already VIP.",
            'NO_FAMILY' => "You can only buy this when you're part of a family.",
            'BOUGHT_STATUS_SUCCESS' => "You bought a {status} status for {credits} credits!",
            'BOUGHT_FAMILY_VIP_SUCCESS' => "You bought a VIP family status for 500 credits!",
            'BOUGHT_LUCKYBOX_SUCCESS' => "You bought {boxes} boxes for {credits} credits!",
            'HALVING_TIMES' => "Halving waiting times for 12 hours",
            'BOUGHT_HALVING_TIMES_SUCCESS' => "You spent 63 credits to halve your waiting times for 12 hours starting from now.",
            'BRIBING_BORDER_PATROL' => "Bribe border patrol for 8 hours",
            'BOUGHT_BRIBING_POLICE_SUCCESS' => "You handed over {credits} credits to the border patrol to be able to smuggle for 8 hours without being caught.",
            'GROUND' => "Extra ground area",
            'BOUGHT_GROUND_SUCCESS' => "You traded 100 credits for an extra area on the ground map.",
            'SMUGGLING_CAPACITY' => "100 extra smuggle capacity",
            'BOUGHT_SMUGGLING_CAPACITY_SUCCESS' => "You bought 100 extra smuggling capacity for 100 credits!",
            'NEW_PROFESSION' => "New profession",
            'SELECT_TAG_CHOOSE' => $registerLangs['SELECT_TAG_CHOOSE'],
            'CARJACKER' => $registerLangs['CARJACKER'],
            'PRISON_BREAKER' => $registerLangs['PRISON_BREAKER'],
            'THIEF' => $registerLangs['THIEF'],
            'PIMP' => $registerLangs['PIMP'],
            'BANKER' => $registerLangs['BANKER'],
            'SMUGGLER' => $registerLangs['SMUGGLER'],
            'INVALID_PROFESSION' => $registerLangs['INVALID_PROFESSION'],
            'BOUGHT_NEW_PROFESSION_SUCCESS' => "For 50 credits, your profession was changed to {profession}.",
            'CONTINUE' => "Continue",
            'CAN_RECEIVE' => "You can receive up to {credits} more credits as a donation reward.",
            'LIMIT_RESET' => "On {date} your limit will be reset to 5,000.",
            'DONATE_BTN_HEAD' => "<h4>Please note</h4><p>Receive credits immediately after a donation of any amount starting from at least 1 euro (&euro;). Donations exceeding 50 euros (&euro;) will only yield up to 5,000 credits each month with the exception of bonus credits and promotions.</p><h4>Safely donate through PayPal</h4>",
            'DONATE_BTN_FOOT' => "<small>All transactions are secured and encrypted before transit.</small><h4>Trouble?</h4><p>Contact an Administrator or <span style='color:#3498db'><a href='mailto:info@".$route->settings['domainBase']."?subject=".$route->settings['gamename']." donation shop trouble'><strong>send us an email</strong></a></span> for help.</p>",
            'DONATE_REWARDED_ALREADY' => "This donation bonus has already been claimed!",
            'DONATE_ERROR' => "An error occured with your donation, contact an Administrator for asistance.",
            'DONATE_SUCCESS' => "Your donation was received and {credits} credits have been added to your account as a reward. Thank you!",
            'DONATE_SUCCESS_HIT_LIMIT' => "You have reached your limit which will reset within 31 days.",
            'DONATE_SUCCESS_LIMIT' => "Your donation was received but because of your limit you didn't receive any credits! Thank you!",
            'DONATOR_LIST_INFO' => "I want to add my username to the donator members list.",
            'LEAVE_DONATOR_LIST_SUCCESS' => "You have left the donator members list.",
            'DONATOR_LIST_APPLICATION_SUCCESS' => "You have successfully registered for the donator members list.",
            "CHANGE_NAME" => "Change your name",
            "NEW_NAME" => "New name",
            "INVALID_USERNAME" => $registerLangs['INVALID_USERNAME'],
            "USERNAME_TAKEN" => $registerLangs['USERNAME_TAKEN'],
            "BOUGHT_NICKNAME_SUCCESS" => "You've changed your name successfully!"
        );
        return $langs;
    }
    
    public function pollLangs()
    {
        $langs = array(
            'NO_ACTIVE_POLL' => "Theres no active poll at the moment.",
            'CLICK_TO_VIEW_HISTORY' => "<strong>Click here</strong> to view the poll history",
            'NO_HISTORY_TO_VIEW' => "There is no poll history to view for the moment.",
            'STARTED_ON' => "Started on",
            'ENDED_ON' => "Ended on",
            'TOTAL_VOTES' => "Total votes",
            'VOTE' => "Vote",
            'INVALID_POLL' => "This poll doesn't exist!",
            'INVALID_ANSWER' => "This answer doesn't exist!",
            'SELECT_ANSWER' => "Select an answer please.",
            'ALREADY_VOTED_THIS_QUESTION' => "You already voted in this poll, thank you!",
            'VOTE_SUCCESS' => "Thank you for voting on this poll, you can view the results below:"
        );
        return $langs;
    }
    
    public function forumLangs()
    {
        $langs = array(
            'VIEW_FORUM_OUTGAME' => "&raquo; Visit the forum with a homepage layout in a new tab.",
            'REACTION' => "Reaction",
            'BY' => "By",
            'ON' => "On",
            'TITLE' => "Title",
            'NEW_TOPIC' => "Create new topic",
            'POST_TOPIC' => "Post topic",
            'NEW_REACTION' => "Reply",
            'PLACE_REACTION' => "Post reply",
            'REACT_FAST' => "React fast",
            'REPLY_TOO_SHORT_OR_LONG' => "Your reaction must be between 2 and 10,000 characters long.",
            'TOPIC_TOO_SHORT_OR_LONG' => "Your topic must be between 2 and 10,000 characters long.",
            'TITLE_TOO_SHORT_OR_LONG' => "Your topic title must be between 2 and 100 characters long.",
            'NO_PERMISSIONS_TO_CREATE_TOPIC' => "You don't have the rights to create a topic in this category.",
            'TOPIC_DOESNT_EXIST' => "Topic doesn't exist!",
            'TOPIC_ADDED_SUCCESS' => "You've added your topic to this category! You will be redirected shortly.",
            'REACTION_ADDED_SUCCESS' => "Success, your reaction was added to this topic! You will be redirected shortly.",
            'EDIT_YOUR_LAST_REACTION' => "Nobody responded after your last reaction, you can edit your last reply.",
            'NO_REACTIONS_YET' => "There are no reactions in this topic yet.",
            'NO_TOPICS_YET' => "There are no topics in this forum yet.",
            'TOPIC_CLOSED' => "This topic is closed!",
            'EDIT_TOPIC' => "Edit topic",
            'EDIT_REACTION' => "Edit reaction",
            'TOPIC_DOESNT_EXIST' => "Reaction doesn't exist!",
            'REACTION_EDITED_SUCCESS' => "Success, your reaction was edited in this topic! You will be redirected shortly.",
        );
        return $langs;
    }
    
    public function shoutboxLangs()
    {
        global $route;
        $langs = array(
            'MESSAGE_NOT_IN_RANGE' => "Your message has to range between 2 and 200 characters!",
            'INVALLID_FAMILY' => "You can't place a message in an other family shoutbox!",
            'SHOUTBOX_RESET' => "Every Sunday at 7PM the shoutbox will reset and messages older than 7 days will be removed. On the shoutbox the same <a href='".$route->getRouteByRouteName('information-rules')."'><strong>Rules</strong></a> apply as in any other part of the game!",
            'WAIT_TILL_SOMEBODY_ELSE_POSTED' => "Please wait until someone else posts a message!",
            'LOOKS_SILENT' => "Looks silent in here."
        );
        return $langs;
    }
    
    public function fiftyGamesLangs()
    {
        $gymLangs = $this->gymLangs();
        $langs = array(
            'CHALLENGE' => $gymLangs['CHALLENGE'],
            'START_GAME' => "Start Game",
            'NO_GAMES_ATM' => "There is no active game at the moment.",
            'CANNOT_PLAY_OWN_GAME' => "You can't interact with your own game.",
            'NOT_ENOUGH_AMOUNT_TYPE' => "You don't have enough {type} to stake in this game.",
            'INVALID_GAME' => "This game doesn't exist. (anymore)",
            'FIFTY_GAME_LOST' => "You've lost your stake of {amount} {type}.",
            'FIFTY_GAME_WON' => "You win this game and doubled your stake of {amount} {type}.",
            'FIFTY_GAME_LOST_CASH' => "You've lost your stake of $&#8203;{amount} {type}.",
            'FIFTY_GAME_WON_CASH' => "You win this game and doubled your stake of $&#8203;{amount} {type}.",
            'ALREADY_STARTED_GAME' => "You already started a game of 50/50 here.",
            'STAKE_BETWEEN_100K_AND_100M' => "You need to stake between $100,000 and $100,000,000 cash.",
            'STAKE_BETWEEN_100_AND_10K' => "You need to stake between 100 and 10,000 hoes.",
            'STAKE_BETWEEN_10_AND_1K' => "You need to stake between 10 and 1,000 honnor points.",
            'INVALID_TYPE' => "Invalid action, reload this page and try again please.",
            'CREATE_GAME_SUCCESS' => "You've started a 50/50 gamme with a stake of {amount} {type}.",
            'CREATE_GAME_SUCCESS_CASH' => "You've started a 50/50 gamme with a stake of $&#8203;{amount} {type}.",
        );
        return $langs;
    }
    
    public function dobblingLangs()
    {
        $langs = array(
            'YOUR_THROW' => "Your throw",
            'HIS_THROW' => "His throw",
            'PLAY_DOBBLING_SUCCESS_BROKE_EVEN' => "You broke even and got your stake back!",
            'PLAY_DOBBLING_SUCCESS_WON' => "You won $&#8203;{profits}!",
            'PLAY_DOBBLING_SUCCESS_LOST' => "You lost $&#8203;{losses}!"
        );
        return $langs;
    }
    
    public function racetrackLangs()
    {
        $langs = array(
            'PLAY_RACETRACK_SUCCESS_WON' => "You won $&#8203;{profits}!",
            'PLAY_RACETRACK_SUCCESS_LOST' => "You lost $&#8203;{losses}!"
        );
        return $langs;
    }
    
    public function rouletteLangs()
    {
        $langs = array(
            'RED' => "Red",
            'BLACK' => "Black",
            'ODD' => "Odd",
            '1_COL' => "1st Col",
            '2_COL' => "2nd Col",
            '3_COL' => "3rd Col",
            'PLAY_ROULETTE_SUCCESS_BROKE_EVEN' => "Ball landed on {rolled} you broke even and got your stake back!",
            'PLAY_ROULETTE_SUCCESS_WON' => "Ball landed on {rolled} you won $&#8203;{profits}!",
            'PLAY_ROULETTE_SUCCESS_LOST' => "Ball landed on {rolled} you lost $&#8203;{losses}!",
        );
        return $langs;
    }
    
    public function slotMachineLangs()
    {
        $langs = array(
            'WINNING_COMBINATIONS' => "Winning combinations",
            'POSITION_DOESNT_MATTER' => "Position doesn't matter",
            'ALL_FRUITS' => "All pieces of fruit",
            'PROFIT' => $this->stockExchangeLangs()['PROFIT'],
            'PLAY_SLOT_MACHINE_SUCCESS_BROKE_EVEN' => "You broke even and got your stake back!",
            'PLAY_SLOT_MACHINE_SUCCESS_WON' => "You won $&#8203;{profits}!",
            'PLAY_SLOT_MACHINE_SUCCESS_LOST' => "You lost $&#8203;{losses}!"
        );
        return $langs;
    }
    
    public function blackjackLangs()
    {
        $langs = array(
            'YOUR' => "Your",
            'POINTS' => "Points",
            'HIS' => "His",
            'PICK_A_CARD' => "Pick a card",
            'QUIT' => "Quit",
            'CARD' => "Card",
            'CARDS' => "Cards",
            'PLAY_BLACKJACK_SUCCESS_BROKE_EVEN' => "You broke even and got your stake back!",
            'PLAY_BLACKJACK_SUCCESS_WON' => "You won $&#8203;{profits}!",
            'PLAY_BLACKJACK_SUCCESS_LOST' => "You lost $&#8203;{losses}!",
            'FRESH_DECK_INFO' => "To combat card counting every game starts with a fresh shuffled deck of cards."
        );
        return $langs;
    }
    
    public function lotteryLangs()
    {
        $langs = array(
            'PRIZE_POOL' => "Prize Pool",
            'LATEST_WINNERS' => "Latest Winners",
            'DAY' => $this->stockExchangeLangs()['DAY'],
            'WEEKLY' => "Weekly",
            'PLACE' => "Place",
            'TICKETS_SOLD' => "Tickets sold",
            'TICKET' => "Ticket",
            'FIRST' => "First",
            'SECOND' => "Second",
            'THIRD' => "Third",
            'FOURTH' => "Fourth",
            'FIFTH' => "Fifth",
            'SIXTH' => "Sixth",
            'SEVENTH' => "Seventh",
            'EIGHTH' => "Eighth",
            'NINTH' => "Ninth",
            'DAILY_AFTER_SUPERPOT' => "New daily draws after the superpot!",
            'HAS_TICKET_FOR_DRAWING' => "You have a ticket with code <strong>{code}</strong> for next drawing!",
            'BOUGHT_TICKET_SUCCESS' => "You've bought a {type} ticket for $&#8203;{price}.",
            'DAY_LOTTERY_INFO' => "The daily draw takes place every day at 7PM.",
            'WEEK_LOTTERY_INFO' => "The weekly draw takes place every Friday at 7PM.",
            'NO_WINNERS_PREVIOUS_DRAW' => "No one won any prize in the last drawing."
        );
        return $langs;
    }
    
    public function profileLangs()
    {
        $langs = array(
            'PROFILE_HEADING' => "{name}'s profile",
            'LAST_ONLINE' => "Last online at",
            'NO_TOPLIST' => "Position in Toplist",
            'REPORT_PROFILE' => "Report profile",
            'NO_PROFILE' => "No profile available",
            'PLAYER_INFO' => "Player info",
            'PROFILE_VIEWS' => " Views",
            'SEND_MESSAGE' => "Send message",
            'SEND_FRIEND_INVITE' => "Send friend invite",
            'PIMP_FOR_PLAYER' => "<input type='submit' name='pimp' class='pimp' value='Pimp'/> for player",
            'ATTACK_PLAYER' => "Attack player",
            'LANGUAGE' => "Language"
        );
        return $langs;
    }
    
    public function crimesLangs()
    {
        global $route;
        $langs = array(
            'SPONTANEOUS' => "Spontaneous",
            'ORGANIZED' => "Organized",
            'CRIME_PROFITS' => "Crime profits",
            'SUCCESS_FAIL_RATIO' => "Success/fail ratio", 
            'RANK_POINTS_COLLECTED' => "Rank points collected", 
            'COMMIT_CRIME' => "Commit crime",
            'SPONTANEOUS_CRIME_INFO' => "Your stats will reset after death.",
            'INVALID_CRIME_SELECTED' => "You can't commit this crime (yet)!",
            'COMMIT_CRIME_SUCCESS' => "You successfully committed this crime, you've stolen $&#8203;{stolenMoney} cash and gained {rankpoints} rankpoint(s)!",
            'COMMIT_CRIME_SUCCESS_BUT_HURT' => "You got hurt and lost <strong>{hurtPercent}%</strong> health and <strong>{bullets}</strong> bullets but you've stolen $&#8203;{stolenMoney} cash and gained {rankpoints} rankpoint(s)!",
            'COMMIT_CRIME_SUCCESS_BUT_HEAT' => "You had to fire off <strong>{bullets}</strong> bullets but you've stolen $&#8203;{stolenMoney} cash and gained {rankpoints} rankpoint(s)!",
            'COMMIT_CRIME_ARRESTED' => "You got caught by the police committing this crime! You were arrested for <strong>90 seconds</strong>.",
            'COMMIT_ORGANIZED_CRIME_ARRESTED' => "You got caught by the police committing this crime! You were arrested for <strong>{prisonTime} seconds</strong>.",
            'COMMIT_ORGANIZED_CRIME_PARTICIPANT_ARRESTED' => "{user} got caught by the police committing this crime! He got arrested for <strong>{prisonTime} seconds</strong>.",
            'COMMIT_CRIME_FAILED' => "The crime you committed has failed.",
            'COMMIT_CRIME_FAILED_AND_HURT' => "The crime you committed has failed. You got hurt and lost <strong>{hurtPercent}%</strong> health and <strong>{bullets}</strong> bullets while losing the cops!",
            'IN_PROGRESS' => "In progress",
            'READY' => "Ready",
            'LEADER' => "Leader",
            'DRIVER' => "Driver",
            'RAIDER' => "Raider",
            'GROUND' => "Ground person",
            'ORGANIZED_CRIME_NEED_LV_15' => "You need atleast level 15 crimes to be able to commit organized crimes!",
            'ORGANIZED_CRIME_INFO' => "50 bullets + equipped firearm required to be able to fight off surprises! Organized crimes can end ugly, visit a hospital in time when you're low on health.",
            'NEED_FIRE_WEAPON_EQUIPPED' => "You need a gun equipped with at least 50 bullets, you can buy a gun <a href='/game/equipment-stores'><strong>here</strong></a> or some bullets (if available) <a href='/game/bullet-factories'><strong>here</strong></a>.",
            'ALREADY_PREPARED_FOR_CRIME' => "You already take part in this organized crime!",
            'INVALID_JOB_SELECTED' => "You've choosen an invalid job task!",
            'PLAYER_NOT_EXPERIENCED_ENOUGH' => "This player is not experienced enough yet!",
            'TYPE_NOT_EXPERIENCED_ENOUGH' => "The choosen {type} is not experienced enough yet!",
            'PLAYER_PART_OF_DIFFERENT_CRIME' => "This player already takes part in this crime with someone else.",
            'TYPE_PART_OF_DIFFERENT_CRIME' => "The {type} you've chooses already takes part in this crime with another crew.",
            'CANNOT_INVITE_CRIME_SELF' => "You can't invite yourself to an organized crime!",
            'SELECTED_PLAYER_MULTIPLE_TIMES' => "You've choosen the same player multiple times!",
            'PREPARE_ORGANIZED_CRIME_2_SUCCESS' => "You invited {username} to join your organized crime as a {job}, wait for his approval.",
            'PREPARE_ORGANIZED_CRIME_3_SUCCESS' => "You prepared yourself for an organized crime with {getaway}, {ground} and {intel}, wait for their approval.",
            'NOT_PREPARED_FOR_CRIME' => "You're not prepared for this crime!",
            'ALREADY_PREPARED_AND_READY' => "You've already prepared yourself for this crime!",
            'VEHICLE_NOT_IN_CURRENT_GARAGE' => "This vehicle is not present in your current garage!",
            'INVALID_WEAPON' => "You've selected and invalid weapon!",
            'INVALID_INTEL' => "You've selected an invalid intel package!",
            'READY_UP_ORGANIZED_CRIME_SUCCESS' => "You're now ready for this organized crime.",
            'LEADER_STOP_ORGANIZED_CRIME_CONFIRM' => "Are you sure you want to stop this organized crime? Your crew will lose all (bought) preparations.<br /><br /><form id='interactOrganizedCrime' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('prepare-organized-crime') ."' data-response='#refreshCrimeResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='id' value='{id}'/><input type='submit' name='stop-confirm' class='button' value='Stop crime'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'LEADER_STOP_ORGANIZED_CRIME_SUCCESS' => "You've stopped the organized crime.",
            'PARTICIPANT_DENY_ORGANIZED_CRIME_CONFIRM' => "Are you sure you want to deny this organized crime?<br /><br /><form id='interactOrganizedCrime' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('prepare-organized-crime') ."' data-response='#refreshCrimeResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='id' value='{id}'/><input type='submit' name='deny-confirm' class='button' value='Deny crime'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'PARTICIPANT_DENY_ORGANIZED_CRIME_SUCCESS' => "You've denied the organized crime.",
            'MEMBER_NOT_READY' => "{user} has to wait {waitingTime} more seconds.",
            'ONE_OR_MORE_IN_PRISON' => "There are one or more participants in prison.",
            'DRIVER_INVITED_TO_ORGANIZED_CRIME_3' => "You've invited {username} as Getaway driver for your organized crime in Las Vegas.",
            'GROUND_INVITED_TO_ORGANIZED_CRIME_3' => "You've invited {username} as Ground person for your organized crime in Las Vegas.",
            'INTEL_INVITED_TO_ORGANIZED_CRIME_3' => "You've invited {username} as Intel for your organized crime in Las Vegas.",
            'EXECUTE_ORGANIZED_CRIME_3' => "You went on a trip to Las Vegas with your crew, you will be notified shortly.",
        );
        return $langs;
    }
    
    public function stealVehiclesLangs()
    {
        $crimesLangs = $this->crimesLangs();
        $langs = array(
            'STOLEN_VEHICLES_PROFITS' => "Total value stolen",
            'SUCCESS_FAIL_RATIO' => $crimesLangs['SUCCESS_FAIL_RATIO'], 
            'RANK_POINTS_COLLECTED' => $crimesLangs['RANK_POINTS_COLLECTED'], 
            'STEAL_VEHICLE' => "Steal vehicle",
            'STEAL_VEHICLES_INFO' => "If you wish to store a vehicle in your current state you will need to buy a Garage. End up buying a garage in every state and be able to travel everywhere with a vehicle.",
            'INVALID_STEAL_VEHICLE_SELECTED' => "You can't steal this vehicle (yet)!",
            'STEAL_VEHICLE_SUCCESS' => "You successfully stole a {stolenVehicle} with {damage}% damage and you also gained {rankpoints} rankpoint(s)!{addSome}{addSome2}{addSome3}<br /><br />{picture}",
            'STEAL_VEHICLE_ARRESTED' => "Your attempt to steal a vehicle <strong>failed</strong>, you were arrested for <strong>150 seconds</strong>.",
            'STEAL_VEHICLE_FAILED' => "You've <strong>failed</strong> to steal a vehicle.",
            'STORE_OR_SELL_VEHIICLE' => "What do you want to do with the vehicle? <button type='button' name='store' class='store_vehicle button'>Move to garage</button>&nbsp;<button type='button' name='sell' class='sell_vehicle button'>Sell $&#8203;{price}</button>",
            'VEHICLE_TOTAL_LOSS_SOLD' => "The vehicle you stole was (nearly) total loss, you sold it for <strong>$&#8203;{price}</strong>.",
            'NO_GARAGE_VEHICLE_SOLD' => "You don't have a garage in {state}, you sold the vehicle for <strong>$&#8203;{price}</strong>.",
            'STORE_VEHICLE_NO_GARAGE' => "You don't have a garage in {state}, you can't store this vehicle.<br />You've sold the vehicle for {price}.",
            'NO_VEHICLE_TO_STORE' => "Unknown vehicle, action aborted.",
            'NOT_ENOUGH_SPACE_GARAGE' => "You don't have enough space in your garage. Sell a vehicle in your garage and come back to obtain this vehicle.",
            'NO_SPACE_VEHICLE_SOLD' => "You didn't have enough space in your garage to store this vehicle, you sold it for <strong>$&#8203;{price}</strong>",
            'VEHICLE_STORED_IN_GARAGE' => "You have moved this vehicle to your garage in the state {state}."
        );
        return $langs;
    }
    
    public function drugsLiquidsLangs()
    {
        $langs = array(
            'LIQUIDS_BREWERY' => "Liquids Brewery",
            'MAX_UNITS_INFO' => "You can manage <strong>{maxUnits} production units</strong> simultaneously.",
            'COSTS' => $this->travelLangs()['COSTS'],
            'PRODUCING' => $this->bulletFactoriesLangs()['PRODUCING'],
            'CREATE' => "Create",
            'SECONDS_TO_GO' => "Seconds to go",
            'DONE' => "Done",
            'PRODUCED' => "Produced",
            'COLLECT' => "Collect",
            'NO_UNITS_PRODUCING' => "There are no units producing at the moment.",
            'NO_UNITS_LEFT_TO_PRODUCE' => "You've reached your maximum producing units capacity.",
            'INVALID_UNIT_TYPE' => "You selected an invalid type!",
            'BOUGHT_ONE_UNIT_SUCCESS' => "You bought a unit of {unit} for $&#8203;{price} and started production.",
            'BOUGHT_MAX_UNITS_SUCCESS' => "You bought {unitAmount} units of {unit} for $&#8203;{price} and started production.",
            'SELECT_VALID_UNITS' => "Please select valid units to collect!",
            'COLLECTED_UNITS_SUCCESS' => "You collected {units} production units for a total of {amount} units!",
            'UNFINISHED_UNITS_NOT_COLLECTED' => "You didn't collect {units} production units because they were not done yet.",
            'INVALID_UNITS_NOT_COLLECTED' => "You selected {units} invalid production units!",
            'CAPACITY_FULL_UNITS_NOT_COLLECTED' => "You didn't collect {units} production units because you didn't have enough capacity left to hold them.",
        );
        return $langs;
    }
    
    public function smugglingLangs()
    {
        $langs = array(
            'LIQUIDS' => "Liquids",
            'FIREWORKS' => "Fireworks",
            'WEAPONS' => ucfirst($this->langMap['WEAPON']."s"),
            'EXOTIC_ANIMALS' => "Exotic animals",
            'PROFIT_INDEX' => "Profit index",
            'SUCCESS_FAIL_RATIO' => $this->crimesLangs()['SUCCESS_FAIL_RATIO'], // Double
            'SMUGGLING_PROFITS' => "Smuggle profits",
            'UNITS_SMUGGLED' => "Smuggled",
            'UNITS_IN_POSSESSION' => "In possession",
            'UNITS_AVAILABLE' => "Free space",
            'EACH' => "Each",
            'VIEWING_PROFIT_INDEX_FOR' => "You're viewing the profit index for",
            'IN_POSSESSION' => "In possession",
            'INVALID_UNIT_SELECTED' => "You've selected an invalid smuggle unit or inserted an invalid amount.",
            'CANNOT_CARRY_THAT_MUCH' => "You cannot caary that much {type}, you're able to carry {units} more units.",
            'DONT_HAVE_THAT_MANY' => "You don't have that many {unitName} in possession.",
            'BOUGHT_X_UNITS_SUCCESS' => "You bought {units} {unitName} for $&#8203;{price}.",
            'SOLD_X_UNITS_SUCCESS' => "You sold {units} {unitName} for $&#8203;{price}.",
            'SMUGGLING_INFO' => "To gain smuggling experience you simply have to buy goods in one city to than travel to any other city and sell your earlier bought goods. Your carrying capacity will increase by a 100 on every rank up."
        );
        return $langs;
    }
    
    public function garageLangs() //Garage & Fam garage langs
    {
        $marketLangs = $this->marketLangs();
        global $route;
        $langs = array(
            'VEHICLES' => "Vehicles",
            'SHOP' => "Shop",
            'VEHICLE_BUSINESS' => "Vehicle Business",
            'SPACE' => "Space",
            'HAS_GARAGE_IN_STATE_ALREADY' => "You already own a garage in this state.",
            'GARAGE_OPTION_DOESNT_EXIST' => "You've selected an invalid garage option.",
            'GARAGE_BOUGHT_IN_STATE' => "You've bought a garage in the state {state}!",
            'NO_GARAGE_IN_STATE' => "You don't have a garage in this state!",
            'NO_SPACE_LEFT_GARAGE_IN_STATE' => "You don't have any space left in your garage in the state {state}!",
            'X_SPACE_LEFT_GARAGE_IN_STATE' => "You have {x} free spots in your garage in {state}!",
            'HAS_FAMILY_GARAGE_ALREADY' => "You already own a family garage.",
            'FAMILY_GARAGE_BOUGHT' => "You bought a family garage, you can now commit family crimes with your family members!",
            'NO_FAMILY_GARAGE' => "You don't have a family garage! Only the boss or underboss can buy one.",
            'NO_SPACE_LEFT_FAMILY_GARAGE' => "You don't have any space left in the family garage!",
            'X_SPACE_LEFT_FAMILY_GARAGE' => "You have {x} free spots in the family garage!",
            'REPAIR' => "Repair",
            'DAMAGE' => $this->equipmentStoresLangs()['DAMAGE'],
            'VALUE' => "Value",
            'TUNE' => "Tune",
            'COSTS' => $this->travelLangs()['COSTS'],
            'VEHICLE_NOT_OWNED_BY_USER' => "You do not own this vehicle (anymore).",
            'VEHICLE_NOT_IN_CURRENT_GARAGE' => $this->crimesLangs()['VEHICLE_NOT_IN_CURRENT_GARAGE'],
            'NO_REPAIR_NEEDED_FOR_VEHICLE' => "This vehicle isn't damaged and doesn't need repairing!",
            'REPAIR_VEHICLE_SUCCESS' => "You repaired this vehicle for $&#8203;{costs}.",
            'SELL_VEHICLE_SUCCESS' => "You sold this vehicle for $&#8203;{price}.",
            'PRICE' => $marketLangs['PRICE'],
            'MORE_INFO' => "More info",
            'VEHICLE_DOESNT_EXIST' => "This vehicle doesn't exist!",
            'NO_GARAGE_WITH_FREE_SPACE' => "You don't have a garage available with atleast 1 free space.",
            'BUY_VEHICLE_CHOOSE_GARAGE' => "Purchase almost successful, to which garage you wish to have your new {vehicle} delivered?<br /><br /><form id='interactVehicleShop' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('garage-interact-vehicle') ."' data-response='#vehicleActionResponse_{vehicleID}'><input type='hidden' name='securityToken' value='{securityToken}'/><input type='hidden' name='id' value='{vehicleID}' /><input type='hidden' name='action' value='bought' />{garagesSelection}</form>",
            'BOUGHT_VEHICLE_SUCCESS' => "Purchase complete, your new {vehicle} has just been delivered for $&#8203;{price}.",
            'HORSEPOWER' => "Horsepower",
            'HP' => "HP",
            'TOPSPEED' => "Topspeed",
            'ACCELERATION' => "Acceleration",
            'CONTROL' => "Control",
            'BREAKING' => "Breaking",
            'LV_TO_STEAL' => "Steal at level",
            'THE' => "The",
            'OF' => "Of",
            'TIRES' => "Tires",
            'ENGINE' => "Engine",
            'EXHAUST' => "Exhaust",
            'SHOCK_ABSORBERS' => "Shock absorbers",
            'OVERVIEW' => $this->possessionsLangs()['OVERVIEW'],
            'TUNE_VEHICLE_DAMAGED' => "Please repair your vehicle first before you perform tune upgrades.",
            'CANNOT_SELL_TUNED_VEHICLE' => "You cannot sell this tuned vehicle, sell your tune upgrades first!",
            'TUNE_ITEM_IN_POSSESSION' => "You already have these tune upgrades installed! Sell your active upgrade first.",
            'TUNE_ITEM_NOT_IN_POSSESSION' => "You don't have these tune upgrades in possession.",
            'BUY_VEHICLE_TUNE_ITEM_SUCCESS' => "You installed new {itemName} {type} for $&#8203;{costs}.",
            'SELL_VEHICLE_TUNE_ITEM_SUCCESS' => "You sold your {itemName} {type} for $&#8203;{price}.",
            /** FAMILY GARAGE LANGS HERE: **/
            'NO_VEHICLES_IN_FAMILY_GARAGE' => "There are no vehicles in your family garage.",
            'SELECT_ONE_OR_MORE_VEHICLES' => "Select one or more vehicles!",
            'SELL_FAMILY_VEHICLES_SUCCESS' => "You've sold all selected vehicles for $&#8203;{money}.",
            'NOT_ENOUGH_CRUSH_CONVERT_CAPACITY' => "You don't have enough crush or convert capacity left. Buy more at the <a href='".$route->getRouteByRouteName('family-garage-crusher-converter')."'><strong>crusher & converter</strong></a>.",
            'CRUSH_CONVERT_FAMILY_VEHICLES_SUCCESS' => "You've crushed and converted all selected vehicles! {bullets} bullets were added to the family warehouse.",
            'CRUSH_CONVERT_FAMILY_VEHICLES_CAP_SUCCESS' => "You've crushed and converted some selected vehicles! {bullets} bullets were added to the family warehouse. {unhandled} vehicles weren't handled buy new <a href='".$route->getRouteByRouteName('family-garage-crusher-converter')."'><strong>crusher & converter</strong></a> capacity.",
            'SMALL' => "Small",
            'MEDIUM' => "Medium",
            'LARGE' => "Large",
            'ITEM_DOESNT_EXIST' => $marketLangs['MARKET_ITEM_DOESNT_EXIST'],
            'FAMILY_BOUGHT_ITEM_ALREADY' => "Your family already bought this!",
            'FAMILY_CRUSHER_BOUGHT' => "You bought a new crusher for the family. The family paid $&#8203;{price}, you can now crush {capacity} vehicles!",
            'FAMILY_CONVERTER_BOUGHT' => "You bought a new converter for the family. The family paid $&#8203;{price}, you can now convert {capacity} vehicles!",
            'FAMILY_HAS_NO_CRUSHER' => "The family has no crusher yet.<br />The boss or bank manager can buy one.",
            'FAMILY_HAS_NO_CONVERTER' => "The family has no converter yet.<br />The boss or bank manager can buy one.",
            'FAMILY_CAN_CRUSH_X_VEHICLES' => "The family can crush <strong>{capacity}</strong> more vehicles.",
            'FAMILY_CAN_CONVERT_X_VEHICLES' => "The family can convert <strong>{capacity}</strong> more vehicles."
        );
        return $langs;
    }
    
    public function streetraceLangs()
    {
        $str = "Streetrace";
        $famCrimeLangs = $this->familyCrimeLangs();
        $langs = array(
            'TITLE' => ucfirst($str),
            'DESCRIPTION' => "Er zijn 4 soorten ".strtolower($str)."s: Highway, Route66, Drift Race en City Race.<br />Voor elk soort race moet je auto goed zijn op andere punten.<br /><br />De winnaar van de ".strtolower($str)." krijgt 3 keer de inzet, en de nummer 2 krijgt zijn inzet terug.<br />De nummer 3 en 4 verliezen hun geld.",
            'ORGANIZE' => $famCrimeLangs['ORGANIZE'],
            'PARTICIPANTS' => $famCrimeLangs['PARTICIPANTS'],
            'JOIN' => $famCrimeLangs['JOIN'],
            'LEAVE' => $this->familyLangs()['LEAVE'],
            'QUIT' => $this->familyRaidLangs()['QUIT'],
            'RESULTS' => "Results",
            'NO_VEHICLE_TO_RACE' => "You don't have a vehicle available in your garages.",
            'ALREADY_PART_OF_RACE' => "You're already part of a ".strtolower($str)."!",
            'NO_PART_OF_RACE' => "You're not part yet of a ".strtolower($str)."!",
            'INVALID_RACE' => "You've choosen an invalid ".strtolower($str)."!",
            'INVALID_RACE_TYPE' => "You've choosen an invalid race type!",
            'INVALID_STAKE' => "You've choosen an invalid stake!",
            'INVALID_VEHICLE' => "You've choosen an unknown vehicle!",
            'RACE_ALREADY_FULL' => "This ".strtolower($str)." is already full!",
            'RACE_NOT_READY_YET' => "This ".strtolower($str)." is not ready yet to be started!",
            'ORGANIZE_RACE_SUCCESS' => "You started a ".strtolower($str)."!",
            'JOIN_RACE_SUCCESS' => "You are now participating in this ".strtolower($str)."!",
            'QUIT_RACE_SUCCESS' => "You've quit this ".strtolower($str)."!",
            'LEAVE_RACE_SUCCESS' => "You left the ".strtolower($str)."!",
            'RACE_SUCCESS_LOST_NTH' => "You finished {nth} in the ".strtolower($str)." and lost your stake!",
            'RACE_SUCCESS_EVEN_SECOND' => "You finished second in the ".strtolower($str)." and won back your stake!",
            'RACE_SUCCESS_WON_FIRST' => "You finished first place in the ".strtolower($str)." and won $&#8203;{price} cash!"
        );
        return $langs;
    }
    
    public function familyLangs() // Family list  ,    page, bank  ,    tiny bit family garage (rest in garage langs)   &    management
    {
        global $route;
        $langs = array(
            'BOSS' => "Boss",
            'BANKMANAGER' => "Bankmanager",
            'UNDERBOSS' => "Underboss",
            'ALLIANCES' => "Alliances",
            'JOIN' => "Join",
            'LEAVE' => "Leave",
            'LEAVE_COSTS' => "Leave costs",
            'INTEREST' => "Interest",
            'MEMBERS_AMOUNT' => "Members amount",
            'SEARCH_FAMILY' => "Search family..",
            'BULLET_FACTORY' => ucwords($this->bulletFactoriesLangs()['BULLET_FACTORY']),
            'BROTHEL' => $this->familyPropertiesLangs()['BROTHEL'],
            'FAMILY_HAS_NO_MISSIONS' => "No achieved missions.",
            'FAMILY_HAS_NO_ALLIANCES' => "No alliances with other families.",
            'FAMILY_HAS_NO_PROPERTIES' => "This family doesn't own any properties.",
            'FAMILY_HAS_NO_PROFILE' => "This family has no family profile.",
            'FAMILY_DOESNT_EXIST' => "This family doesn't exist!",
            'NO_JOINS_ALLOWED' => "This family has disabled joining.",
            'ALREADY_ATTEMPTED_JOIN' => "You've already attempted to join this family, please wait for a reaction from the family boss(es).",
            'ALREADY_INVITED_TO_FAMILY' => "This family has invited you to join them already, please go to <a href='/game/family-invitations'><strong>Family Invitations</strong></a> to accept their request.",
            'USER_ALREADY_ATTEMPTED_JOIN' => "This member has joined already, you can accept this request above.",
            'USER_ALREADY_INVITED_TO_FAMILY' => "This member is already invited for the family.",
            'ALREADY_PART_OF_A_FAMILY' => "Leave your current family first before joining another.",
            'FAMILY_JOINED_SUCCESSFUL' => "You have joined the family <strong>{familyName}</strong>, now you have to wait for a reaction from the family boss or underboss.",
            'FAMILY_BOSS_CANNOT_LEAVE' => "You can't leave the family as their boss, <a href='".$route->getRouteByRouteName('family-management-manage-family')."'><strong>click here</strong></a> to manage the family.",
            'LEFT_FAMILY_SUCCESSFUL' => "You've left {familyName} for an amount of $&#8203;{leaveCosts}.",
            'NO_FAMILIES_FOUND' => "No families were found, please try again with a different keyword.",
            'FAMILY_SEARCH_SUCCESSFUL' => "Search complete, the following families were found:",
            'SEED_MONEY' => "Seed Money",
            'CREATE_FAMILY_INFO' => "If you're part of a family you can optimally use all family benefits! You have to be atleast Legendary Godfather with all levels 10+ to be able to start a family.",
            'CREATE_FAMILY_WARNING' => "Families will die out when all family members are dead for longer than 24 hours, hereby all achieved family progress will get lost. Only create a family yourself when you know your family is going to play the game actively. You can always join an existing family that allows members.",
            'FAMILYNAME_ALREADY_TAKEN' => "This family name is already taken.",
            'INVALID_FAMILYNAME' => "A family name can only contain letters, numbers or a hyphen character(-), minimal 1 letter. range between 3-15 characters.",
            'CANNOT_CREATE_FAMILY' => "You do not meet the requirements to start a family, train some more and try again later on.",
            'CREATE_FAMILY_SUCCESS' => "You've created the family named <strong>{familyName}</strong> with <strong>$&#8203;{seedMoney}</strong> seed money",
            'FAMILY_PAGE_HEADING' => "Family page of {familyName}",
            'DONATE_FAMILY_SUCCESS' => "You have donated <strong>$&#8203;{amount}</strong> to your family minus some transaction costs.",
            'USER_NOT_INSIDE_FAMILY' => "This member in not a part of your family.",
            'USER_ALREADY_IN_DIFFERENT_FAMILY' => "This member is already part of another family.",
            'NOT_ENOUGH_MONEY_FAMBANK' => "There's not enough money on your family bank.",
            'NO_RIGHTS_FAMILY_BANK' => "You don't have bank manage rights for your family.",
            'NO_RIGHTS_FAMILY_MANAGEMENT' => "You don't have the rights to manage this family.",
            'BANK_TRANSFER_FAMILY_SUCCESS' => "You have transfered <strong>$&#8203;{amount}</strong> to <strong>{user}</strong>'s bank.",
            'JOINED_MEMBERS' => "Joined members",
            'INVITED_MEMBERS' => "Invited members",
            'KICK_MEMBERS' => "Kick members",
            'INVITE_MEMBERS' => "Invite members",
            'JOIN_POLICY' => "Join policy",
            'MEMBERS_MAY_JOIN' => "Members <strong>may</strong> join.",
            'MEMBERS_MAY_NOT_JOIN' => "Members <strong>may not</strong> join.",
            'INVITE' => "Invite",
            'NO_JOINED_MEMBERS_YET' => "Nobody has joined the family until now.",
            'NO_INVITED_MEMBERS_YET' => "Nobody is invited for the family until now.",
            'NO_BANK_LOGS_TO_VIEW' => "No bank logs to view.",
            'USER_DID_NOT_JOIN' => "This member did not join the family.",
            'HANDLE_JOIN_SUCCESS' => "You have {commitedAction} {user}.",
            'USER_NOT_INVITED' => "This member was not invited to the family.",
            'HANDLE_INVITE_SUCCESS' => "You have {commitedAction} the invitation for {user}.",
            'KICK_FAMILY_MEMBER_SUCCESS' => "You've kicked {user} out of the family.",
            'INVITE_FAMILY_MEMBER_SUCCESS' => "You've invited {user} to join the family.",
            'CHANGE_JOINPOLICY_SUCCESS' => "You have changed the join policy, the family is now {openclosed}.",
            'OPEN' => "Open",
            'CLOSED' => "Closed",
            'IMAGE' => "Image",
            'UPLOAD_FAMILY_IMAGE_WRONG_FILE' => "Uploading a family {type} is only compatible with the following extensions .jpg, .png or .gif",
            'UPLOAD_FAMILY_IMAGE_SUCCESS' => "You've uploaded a new family {type}.",
            'UPLOAD_FAMILY_IMAGE_FAILED' => "An error occured while uploading a family {type}, please try again.",
            'FAMILY_PROFILE_TO_LONG' => "Your family profile can't be longer than {max} characters.",
            'FAMILY_MESSAGE_TO_LONG' => "Your family message can't be longer than 10,000 characters.",
            'UPDATE_FAMILY_PROFILE_SUCCESS' => "You've updated your family profile!",
            'UPDATE_FAMILY_MESSAGE_SUCCESS' => "You've updated your family message!",
            'NO_FAMILY_INVITATIONS_YET' => "You have no familie invitations at the moment.",
            'ALREADY_IN_DIFFERENT_FAMILY' => "You're already part of a different family, leave your current family first.",
            'INVALID_FAMILY_INVITATION' => "You've selected an invalid invitation.",
            'HANDLE_INVITATION_SUCCESS' => "You've {committedAction} the family invitation of {family}!{add}",
            'NOW_MEMBER_OF_FAMILY' => " You're now a member of the family.", /** additional {add} of above^ **/
            'NO_FAMILY_MESSAGE' => "There's no family message available.",
            'ABOLISH' => "Abolish",
            'ABOLISH_FAMILY_AND_PROGRESS' => "Do you want to remove the family and it's progress?",
            'ABOLISH_FAMILY_CONFIRM' => "Are you sure you want to abolish the entire family? <strong>{username}</strong> will miss the family!<br /><br /><form id='abolishFamilyConfirm' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('abolish-family') ."' data-response='#abolishFamilyResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='submit' class='btn button alert-btn' name='abolish-confirm' value='Abolish!'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'ABOLISH_FAMILY_SUCCESS' => "You've deleted your family and their progress.",
            'UNLIMITED' => "Unlimited",
            'UP_TO' => "Up to",
            'EACH_DAY' => "each day",
            'FAMILY_MANAGEMENT_ONLY_BOSS' => "Only the family boss can manage this part of the settings.",
            'USER_ALREADY_IN_FAMILY_TOP' => "This member is already a part of the family top.",
            'FAMILY_BOSS_REQUIRED' => "A family boss is required, please see the option below to abolish your family!",
            'FAMILY_BOSS_CHANGE_CONFIRM' => "Are you sure you want to transfer the family lead to <strong>{username}</strong>? You will lose all control over the family once you transfer leadership.<br /><br /><form id='manageFamilyBossConfirm' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('manage-family-top') ."' data-response='#manageFamilyTopResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='boss-confirm' value='{username}'/><input type='submit' class='btn button alert-btn' name='boss-change-confirm' value='".$this->langMap['TRANSFER']."'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'FAMILY_NEEDS_VIP_STATUS' => "The family needs a VIP status for this. <a href='".$route->getRouteByRouteName('donation-shop')."'><strong>Click here</strong></a> to buy a VIP family status.",
            'FAMILY_TOP_STATUS_SET_TO_USER' => "You have set {username} as the new {statusName} of the family!",
            'FAMILY_TOP_STATUS_SET_EMPTY' => "You have removed the {statusName} of the family! This status is now empty again.",
            'ALLIANCE' => "Alliance",
            'REQUEST' => "Request",
            'FAMILY_MANAGEMENT_NO_ALLIANCES' => "The family has no active or pending alliances yet.",
            'ALREADY_PENDING_OR_ALLIANCE' => "You already have an active / pending alliance with this family.",
            'NO_ALLIANCE_WITH_SELF' => "You cannot enter into an alliance with your own family!",
            'FAMILY_ALLIANCE_INVITE_SUCCESS' => "You've sent an alliance request to the family {family}.",
            'ALREADY_ACTIVE_ALLIANCE' => "You already have an alliance with this family.",
            'NO_PENDING_OR_ALLIANCE' => "You don't have an pending or active alliance with this family.",
            'FAMILY_HANDLE_ALLIANCE_SUCCESS' => "You have {type} the family alliance with {family}!",
            'BETWEEN_0_AND_100K' => "You need to insert an amount between $0 and $100,000!",
            'MANAGE_LEAVE_COSTS_SUCCESS' => "You've set the leave costs to $&#8203;{costs}.",
            'SEND_FAMILY_MASS_MESSAGE_SUCCESS' => "You've sent a mass message to all your family members.",
            'NO_DONATIONS_TO_VIEW' => $this->bankLangs()['NO_DONATIONS_TO_VIEW']
        );
        return $langs;
    }
    
    public function familyPropertiesLangs()
    {
        $bfLangs = $this->bulletFactoriesLangs();
        $langs = array(
            'BULLET_FACTORY' => ucwords($bfLangs['BULLET_FACTORY']),
            'BROTHEL' => "Brothel",
            'FAMILY_BF_IS_CURRENTLY' => "The family's bullet factory is currently",
            'PRODUCING' => $bfLangs['PRODUCING'],
            'DORMANT' => "Dormant",
            'PRODUCE' => "Produce",
            'HOUR' => "Hour",
            'PROFIT' => $this->stockExchangeLangs()['PROFIT'],
            'ADD' => "Add",
            'TAKE_AWAY' => $this->redLightDistrictLangs()['TAKE_AWAY'],
            'HAS_NO_PROPERTY_TYPE' => "The family has no {property} yet, the boss or bankmanager can buy one.",
            'NO_RIGHTS_FAMILY_PROPERTY' => "You don't have rights to manage properties.",
            'INVALID_PROPERTY' => "Invalid property selected!",
            'ALREADY_OWN_PROPERTY' => "You already own this property!",
            'CANNOT_UPGRADE_PROPERTY' => "You cannot upgrade this property!",
            'BOUGHT_PROPERTY_SUCCESS' => "You bought a {property} for the family!",
            'UPGRADE_PROPERTY_SUCCESS' => "You've upgraded the family {property} for $&#8203;{price}.",
            'INVALID_PRODUCTION' => "You selected an invalid production unit.",
            'SET_BF_PRODUCTION_SUCCESS' => "You have set the bullet factory production to {production} bullets each hour.",
            'BETWEEN_1_AND_999K_BULLETS' => "You need to range between 1 and 999,999 bullets!",
            'USER_NOT_ENOUGH_BULLETS' => "You don't have that many bullets!",
            'NOT_ENOUGH_SPACE_BF' => "Amount exceed the capacity of the bullet factory!",
            'DONATE_BULLETS_SUCCESS' => "You donated {bullets} bullets to the family's bullet reserve.",
            'NOT_ENOUGH_FAMILY_BULLETS' => "The family doens't have that many bullets!",
            'BETWEEN_100_AND_100K_BULLETS' => "You need to range between 100 and 100,000 bullets!",
            'SEND_FAMILY_BULLETS_SUCCESS' => "You've sent {bullets} bullets to {user}.",
            'BETWEEN_1_AND_99K_WHORES' => "You need to range between 1 and 99,999 hoes!",
            'NOT_ENOUGH_SPACE_BROTHEL' => "Amount exceed the capacity of the brothel!",
            'ADD_WHORES_SUCCESS' => "You've added {whores} street hoes to the family brothel.",
            'TAKE_AWAY_WHORES_SUCCESS' => "You've removed {whores} hoes from the family brothel back to the streets.",
        );
        return $langs;
    }
    
    public function familyCrimeLangs() 
    {
        $famLangs = $this->familyLangs();
        global $route;
        $langs = array(
            'NO_FAMILY_GARAGE' => "You need a family garage to commit family crimes.",
            'NO_FAMILY_GARAGE_WITH_LINK' => "You need a family garage to commit family crimes, the family boss or underboss can buy one <a href='".$route->getRouteByRouteName('family-garage')."'><strong>here</strong></a>.",
            'PARTICIPANTS' => "Participants",
            'CAR_FAIR' => "Car fair",
            'ORGANIZE' => "Organize",
            'LEAVE' => $famLangs['LEAVE'],
            'JOIN' => $famLangs['JOIN'],
            'NO_FAMILY_CRIMES_FOUND' => "No family crimes in preperation have been found.",
            'INVALID_PARTICIPANTS_SELECTED' => "You selected an invalid amount of participants.",
            'INVALID_CRIME_SELECTED' => "You selected an invalid crime.",
            'ALREADY_INSIDE_FAMILY_CRIME' => "You're already part of an organized crime, leave that one first.",
            'ORGANIZE_FAMILY_CRIME_SUCCESS' => "You organized a {crime} for you and your family members in {state}.",
            'FAMILY_CRIME_FULL' => "This family crime seems to be full.",
            'NOT_INSIDE_SAME_STATE' => "Your not in the same state as the family crime.",
            'NOT_PART_OF_FAMILY_CRIME' => "You're not a part of this family crime!",
            'MEMBER_NOT_READY' => "{user} is not ready yet and has to wait {waitingTime} more seconds.",
            'ONE_OR_MORE_IN_PRISON' => "There are one or more family members in prison.",
            'NOT_ENOUGH_MEMBERS_YET' => "There are not enough members to commit this crime.",
            'JOINED_FAMILY_CRIME' => "You're now a part of this family crime!",
            'LEFT_FAMILY_CRIME' => "You left this family crime!",
            'LEFT_FAMILY_CRIME_AS_LAST' => "You left this family crime! Becasue you were the last participant this crime is removed!",
            'FAMILY_CRIME_FAILED' => "The family crime failed!",
            'ARE_ALSO_IMPRISONED' => "are also imprisoned!",
            'FAMILY_CRIME_SUCCESS' => "The family crime has succeeded, you stole a {carName} with {damage}% damage!<br /><br /><img src='{imageSrc}' class='middle' alt='{carName}'/>",
            'FAMILY_CRIME_SUCCESS_GARAGE_FULL' => "The family crime has succeeded, you stole &amp; sold a {carName} with {damage}% damage for $&#8203;{price} due to a full garage!<br /><br /><img src='{imageSrc}' class='middle' alt='{carName}'/>",
            'FAMILY_CRIME_SUCCESS_MULTIPLE' => "The family crime has succeeded, following vehicles have been stolen:<br />{add}<br /><br /><br />{add2}<br /><br /><br />{add3}",
            'FAMILY_CRIME_MULTIPLE_ADD' => "a {carName} with {damage}% damage!<br /><br /><img src='{imageSrc}' class='middle' alt='{carName}'/>",
            'FAMILY_CRIME_MULTIPLE_GARAGE_FULL_ADD' => "a sold {carName} with {damage}% damage for $&#8203;{price} due to a full garage!<br /><br /><img src='{imageSrc}' class='middle' alt='{carName}'/>",
        );
        return $langs;
    }
    
    public function familyRaidLangs()
    {
        $famCrimeLangs = $this->familyCrimeLangs();
        $crimesLangs = $this->crimesLangs();
        global $route;
        $langs = array(
            'NOT_IN_RAID_INFO' => "You are not participating in a raid yet!<br />You can organize a raid below.",
            'LEADER' => $crimesLangs['LEADER'],
            'DRIVER' => $crimesLangs['DRIVER'],
            'BOMB' => "Bomb",
            'ORGANIZE' => $famCrimeLangs['ORGANIZE'],
            'READY' => $crimesLangs['READY'],
            'WAITING' => "Waiting",
            'QUIT' => "Quit",
            'BULLETS_INFO' => "$50 each bullet, between 50 and 1,000 bullets!",
            'RAID_TYPE_NOT_AVAILABLE' => "The {type} you selected is not available right now.",
            'RAID_TYPE_NOT_IN_FAMILY' => "The {type} you selected is not inside your family!",
            'ALREADY_INSIDE_FAMILY_RAID' => "You're already a part of an organized raid, leave that one first.",
            'SELECTED_USER_MULTIPLE_TIMES' => "You selected the same member multiple times!",
            'ORGANIZE_FAMILY_RAID_SUCCESS' => "You organized e new family raid, wait for your crew to ready up.",
            'NOT_PARTICIPATING_IN_RAID' => "You are not a participating in a raid!",
            'VEHICLE_NOT_IN_CURRENT_GARAGE' => $crimesLangs['VEHICLE_NOT_IN_CURRENT_GARAGE'],
            'NOT_IN_SAME_STATE_AS_RAID' => "You need to be in the state <strong>{state}</strong> to be able to participate in this raid.",
            'ALREADY_PREPARED' => "You're already prepared for this family raid!",
            'INVALID_EQUIPMENT' => "You selected invalid equipment!",
            'QUIT_RAID_MESSAGE' => "Are you sure you want to quit this raid? You will lose all (bought) preparations.<br /><br /><form id='interactFriendsList' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('interact-family-raid') ."' data-response='#familyRaidResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='familyRaidID' value='{frid}'/><input type='submit' name='{typeRaw}-quit-confirm' class='button' value='Quit raid'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'PREPARE_RAID_TYPE_SUCCESS' => "You've prepared yourself as a {type} for this raid.",
            'DENY_RAID_SUCCESS' => "You denied to be a part of this raid.",
            'QUIT_RAID_SUCCESS' => "You've quitted the organized raid and lost all (bought) items.",
            'ONLY_LEADER_CAN_START_RAID' => "Only the raid leader can start this raid.",
            'RAID_PARTICIPANT_CHANGED' => "You've changed the participant of this raid.",
            'LEADER_QUIT_RAID_CONFIRM' => "Are you sure you want to quit this raid? Your crew will lose all (bought) preparations.<br /><br /><form id='interactFriendsList' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('interact-family-raid') ."' data-response='#familyRaidResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='familyRaidID' value='{frid}'/><input type='submit' name='quit-confirm' class='button' value='Quit raid'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'LEADER_QUIT_RAID_SUCCESS' => "You've quitted this family raid, you and your crew are free to start a new one.",
            'RAID_NOT_READY_TO_START' => "Not all participants are ready yet to start this raid.",
            'ONE_OR_MORE_IN_PRISON' => $famCrimeLangs['ONE_OR_MORE_IN_PRISON'],
            'START_FAMILY_RAID_SUCCESS' => "You have robbed the bank of <strong>{state}</strong>!<br />You have stolen <strong>$&#8203;{stolenAmount}</strong> in total, that's <strong>$&#8203;{stolenEach}</strong> for each person!",
            'START_FAMILY_RAID_FAIL' => "The robbery on the bank of <strong>{state}</strong> has <span class='crimson'><strong>failed</strong></span>!",
            'HIRE_VEHICLE' => "Hire vehicle (+$15,000)",
            'HIRED_VEHICLE' => "Hired vehicle",
        );
        return $langs;
    }
    
    public function familyMercenariesLangs()
    {
        $langs = array(
            'BOUGHT' => "Bought",
            'AVAILABLE' => "Available",
            'USED' => "Used",
            'BUYER' => "Buyer",
            'MERCENARIES_INFO' => "Mercenaries cost $&#8203;{price} each and are able to back up all family members in family crimes.",
            'NO_MERCENARIES_BOUGHT' => "The family hasn't bought any mercenaries yet.",
            'BUY_BETWEEN_2_AND_999_MERCENARIES' => "You can only buy between 2 and 999 mercenaries at once!",
            'BOUGHT_MERCENARIES_SUCCESS' => "You've bought {mercenaries} mercenaries for $&#8203;{price}."
        );
        return $langs;
    }
    
    public function onlineToplistLangs()
    {
        $langs = array(
            'NO_MEMBERS_ONLINE' => "There are no members online.",
            'NO_FAM_MEMBERS_ONLINE' => "There are no family members online.",
            'NO_TEAM_MEMBERS_ONLINE' => "There are no team members online.",
            'SEARCH_BY_RANK' => "Search by rank",
            'SEARCH_PLAYER' => "Search player..",
            'NO_PLAYERS_FOUND' => "No players found, please try again with a different keyword.",
            'USER_SEARCH_SUCCESSFUL' => "Search complete, following player(s) have been found:",
            'BLOCK_VIEW' => "Grid view",
            'LIST_VIEW' => "List view",
        );
        return $langs;
    }
    
    public function informationLangs()
    {
        global $route;
        $langs = array(
            'STATISTICS' => "Statistics",
            'RULES' => "Rules",
            'THANKS_TO_ALL_DONATORS' => "We like to thank all paid players! Without them ".$route->settings['gamename']." wouldn't have existed.",
            'AMOUNT_OF_MEMBERS' => "Amount of members",
            'TOTAL_CASH_MONEY' => "Total cash money",
            'TOTAL_BANK_MONEY' => "Total bank money",
            'TOTAL_MONEY' => "Total money",
            'AVERAGE_MONEY_MEMBER' => "Average money each member",
            'AMOUNT_OF_FAMILIES' => "Amount of families",
            'TOTAL_MEMBER_BULLETS' => "Total member bullets",
            'AVERAGE_BULLETS_MEMBER' => "Average bullets each member",
            'DEAD_MEMBERS_NOW' => "Dead members at the moment",
            'TOTAL_BANNED_PLAYERS' => "Total banned players",
            'RICHEST' => "Richest",
            'PLAYER' => "Player",
            'MOST_HONORABLE' => "Most honorable",
            'NEWEST' => "Newest",
            'MOST_SUCCESSFUL_BUSTS' => "Most prison busts",
            'MOST_STOLEN_VEHICLES' => "Most vehicles stolen",
            'MOST_SUCCESSFUL_CRIMES' => "Most successful crimes",
            'MOST_WHORES_PIMPED' => "Most hoes pimped",
            'MOST_UNITS_SMUGGLED' => "Most units smuggled",
            'POPULATION' => "Population",
            'MOST_REFERRAL_PROFITS' => "Most referral profits",
            'VIEWING_ROUND_FROM' => "You're viewing the hall of fame from round",
            'CURRENT' => $this->murderLangs()['CURRENT'],
            'ROUND_PLAYED_FROM' => "This round was played from",
            'NOW' => "Now", // Override
            'TO' => "Up to", // Override
            'OF_WHICH_ARE_BANNED' => "Of which are banned"
        );
        return $langs;
    }
    
    public function ranksScoreLangs()
    {
        global $route;
        $langs = array(
            'WHO_CAN_I_ATTACK' => "Who can I attack?",
            'OPTIONS' => "Options",
            'REQUIREMENTS' => "Requirements",
            'SHADOW' => "Shadow",
            'WITHOUT_PROTECTION' => "Without protection",
            'RANKS_SCORE_INFO' => "<p>* Max. = maximum, Min. = minimum.</p><p>When you're attacked than you can always attack back atleast once within the 24h, no matter what rank.</p><p>Scums that haven't been online for over 5 days can always be attacked no matter your rank!</p><p>Dead players that haven't been online for over 7 days will come back to live every night.</p><p>The personal score is being checked and updated every hour.<br />The score is based on your amount of hoes, honor points, kills, ground area's on the ground map and your rank.<br />The more you have the more score you will receive, you can check your score each hour on the <a href='". $route->getRouteByRouteName('status') ."'><strong>status page</strong></a>.</p><p>Every <a href='". $route->getRouteByRouteName('gym') ."'><strong>gym</strong></a> competition always yields 25 ore more score.<br />In addition, score can occasionally be collected with <a href='". $route->getRouteByRouteName('daily-challenges') ."'><strong>daily challenges</strong></a> & missions >&nbsp;<a href='". $route->getRouteByRouteName('missions-public-mission') ."'><strong>public mission</strong></a>.</p>",
        );
        return $langs;
    }
    
    public function settingsLangs()
    {
        global $route;
        $langs = array(
            'EMAIL_UNKNOWN_IP_DETECTED' => "You can't change your email because your IP is not recognized as safe just yet. This takes up to 24 hours after a new IP address is detected.",
            'CHANGE_EMAIL_NEED_TO_VERIFY' => "We've send an email change request to your previous email adress, a tip: {coveredEmail}<br />Beware! The link to change your email will expire in 2 hours from now.",
            'SAME_EMAIL_NO_CHANGE' => "You can't change the email in the current set email.",
            'CHANGE_EMAIL_DEACTIVATE_PRIVATEID' => "To change your email address, your PrivateID must first be deactivated. After the email change you can generate a new PrivateID.",
            'CHANGE_EMAIL_MESSAGE' => "Dear {username}<br /><br />We received a request to change your email adres into: <strong>{newEmail}</strong><br />If you did not request such change it's recommended to change your password asap to avoid complications.<br /><br />To change your email adres click or copy the following URL in your address bar: <a href='".PROTOCOL.strtolower($route->settings['domain'])."/change-email/key/{key}'>".PROTOCOL.strtolower($route->settings['domain'])."/change-email/key/{key}</a><br />After that follow the instructions on your sreen.<br />If you land on a not found page than the above link is expired. You can make a new request in-game on ".strtolower($route->settings['domain'])."<br /><br /><br />With kind regards<br />".ucfirst($route->settings['domainBase']),
            'CHANGE_EMAIL_SUBJECT' => "Change your email on ".$route->settings['gamename']."",
            'TESTAMENT_NOT_FOR_OWN' => "You can't set your testament to your own account!",
            'CHANGE_TESTAMENT_SUCCESS' => "You have transfered your testament to <strong>{username}</strong>!",
            'UPLOAD_AVATAR_WRONG_FILE' => "Uploading an avatar is only compatible with the following extensions .jpg, .png or .gif",
            'UPLOAD_AVATAR_FAILED' => "An error occured while uploading a new avatar, please try again!",
            'UPLOAD_AVATAR_SUCCESS' => "You've updated your avatar successfully!",
            'PROFILE_TO_LONG' => "Your profile can't be longer than {max} characters.",
            'UPDATE_PROFILE_SUCCESS' => "You've updated your profile!",
            'EDIT_EMAIL' => "Edit email",
            'ENCRYPTED' => "Encrypted",
            'TRANSFER_TESTAMENT' => "Transfer testament",
            'UPLOAD_AVATAR' => "Upload Avatar",
            'EDIT_PROFILE' => "Edit profile",
            'EDIT_PASSWORD' => "Change your password",
            'OLD_PASSWORD' => "Current password",
            'NEW_PASSWORD' => "New password",
            'NEW_PASSWORD_CONFIRM' => "Confirm new password",
            'PASSWORDS_DONT_MATCH' => "The new passwords didn't match!",
            'OLD_PASSWORD_INCORRECT' => "The current password is incorrect, try again.",
            'INVALID_NEW_PASS' => "Your new password has to be atleast 6 characters long!",
            'PASSWORD_UNKNOWN_IP_DETECTED' => "You can't change your password because your IP is not recognized as safe yet. This takes up to 24 hours after a new IP address is detected.",
            'PASSWORD_CHANGE_SUCCESS' => "You successfully changed your password. For security reasons we've logged you out everywhere.",
            'PASSWORD' => "Password",
            'PRIVATEID_GRADE_1' => "4 Characters - Good",
            'PRIVATEID_GRADE_2' => "5 Characters - Very good",
            'PRIVATEID_GRADE_3' => "6 Characters - Excellent",
            'GENERATE' => "Generate",
            'DEACTIVATE' => "Deactivate",
            'NOT_ACTIVE' => "Not active",
            'PRIVATEID_INFO' => "PrivateID allows you to set-up a hidden username for your account, once activated you'll only be able to login with your PrivateID in the username field.<br /><strong>Careful!</strong> PrivateID is only visible during each generation after which it's irreversibly stored in our system. The latest generated PrivateID will always be the correct one unless it got deactivated.<br /><strong>".strtolower($route->settings['domain'])."/recover-password</strong> is used to deactivate a lost PrivateID. (Logout & email access required)",
            'PRIVATEID_UNKNOWN_IP_DETECTED' => "You can't alter your PrivateID because your IP is not recognized as safe yet. This takes up to 24 hours after a new IP address is detected.",
            'PRIVATEID_ALREADY_ACTIVE' => "Deactivate your PrivateID before generating a new one.",
            'ACTIVATE_PRIVATEID_SUCCESS' => "You can now only log in with this case sensitive hidden username: <code><strong>{pid}</strong></code><br />Do not store this digitally to guarantee a higher security of your PrivateID.",
            'PRIVATEID_NOT_ACTIVE' => "You don't have a PrivateID active at this moment.",
            'DEACTIVATE_PRIVATEID_SUCCESS' => "You deactivated your PrivateID, you can log back in using your regular username.",
            'PRIVATEID_INCORRECT' => "The PrivateID you enetered is incorrect.",
        );
        return $langs;
    }
    
    public function messagesLangs()
    {
        $langs = array(
            'MESSAGE_NOT_IN_RANGE' => "Your message must range between 2 and 1,000 characters!",
            'NEW_MESSAGE' => "New message",
            'NO_MESSAGE_TO_SELF' => "No! You can't send a message to yourself..",
            'LAST_ON' => "Last on",
            'NO_MESSAGES_TO_VIEW' => "No messages to view",
            'WROTE_ON' => "Wrote on",
            'NO_MESSAGES_INFO' => "No messages found in current conversation, Do you want to <a href=\"javascript:void(0);\" onclick=\"document.getElementById('new-msg').style.display='block';document.getElementById('new-msg-info').style.display='none';\"><strong>Send a new message</strong></a> to someone?",
            'ALL_THESE' => "All these",
            'SELECTED' => "Selected",
            'CONVERSATION' => "Conversation",
            'MARKUP' => "Markup",
            'BOLD' => "Bold",
            'ITALIC' => "Italic",
            'UNDERLINE' => "Underline",
            'STRIKETROUGH' => "Striketrough",
            'FONT_SIZE' => "Font size",
            'FONT_COLOR' => "Font color"
        );
        return $langs;
    }
    
    public function notificationsLangs()
    {
        $langs = array(
            'NOTIFICATION' => "Notification",
            'NO_NOTIFICATIONS_TO_VIEW' => "You have no notifications yet."
        );
        return $langs;
    }
    
    public function friendsBlockLangs()
    {
        global $route;
        $langs = array(
            'BLOCKED' => "Blocked",
            'BLOCKLIST' => "Blocklist",
            'INVITE_FRIEND' => "Invite as friend",
            'BLOCK_USER' => "Block user",
            'ALREADY_FRIENDS_WITH_USER' => "This player is already in your friends list.",
            'CANNOT_BECOME_FRIENDS' => "You cannot become friends with this player.",
            'ALREADY_REQUESTED_FRIENDSHIP' => "You already requested this player to become friends, you have to wait for a response.",
            'CANNOT_BLOCK_FRIEND' => "You cannot block a friend, remove this friend first.",
            'USER_ALREADY_BLOCKED' => "You already blocked this user.",
            'USER_MAX_FRIEND_BLOCK' => "You already have {max} friends / blocks!",
            'INVITE_FRIEND_SUCCESS' => "You've send a friend request to <strong>{user}</strong>.",
            'BLOCK_USER_SUCCESS' => "You successfully blocked {user}.",
            'DELETE_FRIEND_CONFIRM' => "Are you sure you want to remove <strong>{username}</strong> as a friend?<br /><br /><form id='interactFriendsList' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('interact-friends-list') ."' data-response='#interactFriendslistResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='friend' value='{fid}'/><input type='submit' class='btn button alert-btn' name='delete-confirm' value='".$this->langMap['DELETE']."'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'FRIEND_DELETED' => "You have deleted <strong>{username}</strong> as a friend.",
            'BLOCK_DELETED' => "You have deleted <strong>{username}</strong> from your block list.",
            'FRIEND_REQUEST_ACCEPTED' => "You have accepted the friend request from <strong>{username}</strong>.",
            'FRIEND_REQUEST_DENIED' => "You have denied the friend request from <strong>{username}</strong>."
        );
        return $langs;
    }
    
    public function luckyboxLangs()
    {
        $langs = array(
            'MORE_INFO' => $this->garageLangs()['MORE_INFO'],
            'YOU_HAVE' => "You have",
            'NO_LUCKY_BOX' => "You don't have a lucky box.",
            'OPEN_BOX_SUCCESS' => "You found {amount} {prize} inside!", 
            'PRIZE' => "Prize",
            'CHANCE' => "Chance",
            'BETWEEN' => "Between",
            'AND' => "And",
            'TO' => "To", // Unnecessary but added for same amt. of lines (is translated differently in dutch version)
            'LUCKY_BOX_INFO' => "Lucky boxes disappear when you die, you can hold up to <strong>{boxes} boxes</strong> before you won't be able to find any more in {gamename}!",
            'PLEASE_CALM_DOWN' => "Please calm down.",
        );
        return $langs;
    }
    
    public function captchaTestLangs()
    {
        $langs = array(
            'CAPTCHA_TEST_TITLE' => "Please verify this code before continuing",
            'NEW_CODE' => "New code pls"
        );
        return $langs;
    }
    
    public function restInPeaceLangs()
    {
        $registerLangs = $this->registerLangs();
        $langs = array(
            'YOUR_DEAD' => "Oh dear, you're dead",
            'RETRY' => "Try again",
            'SELECT_TAG_CHOOSE' => $registerLangs['SELECT_TAG_CHOOSE'],
            'CARJACKER' => $registerLangs['CARJACKER'],
            'PRISON_BREAKER' => $registerLangs['PRISON_BREAKER'],
            'THIEF' => $registerLangs['THIEF'],
            'PIMP' => $registerLangs['PIMP'],
            'BANKER' => $registerLangs['BANKER'],
            'SMUGGLER' => $registerLangs['SMUGGLER'],
            'INVALID_USERNAME' => $registerLangs['INVALID_USERNAME'],
            'USERNAME_TAKEN' => $registerLangs['USERNAME_TAKEN'],
            'INVALID_PROFESSION' => $registerLangs['INVALID_PROFESSION'],
        );
        return $langs;
    }
}
