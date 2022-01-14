<?PHP

/**
 *  - $routeGET: Outgame social media sharing, allow any and all GET parameters in request URI
 *  - $routeLang: Outgame multilingual SEO purposes
 **/
         
$applicationRoutes =
array(
    /**
     * @RouteName
     **/
    'home'
    => 
    array(
        /**
         * @RoutePath
         **/
        'route' => $routeLang . '/' . $routeGET,
        
        /**
         * @Controller
         **/
        'controller' => 'index.php'
    ),
    
    'set_lang_nl'
    => 
    array(
        'route' => '/set/language/nl',
        'controller' => 'languageSelect.php'
    ),
    
    'set_lang_en'
    => 
    array(
        'route' => '/set/language/en',
        'controller' => 'languageSelect.php'
    ),
    
    'login'
    =>
    array(
        'route' => $routeLang . '/login' . $routeGET,
        'controller' => 'login.php'
    ),
    
    'screenshots'
    =>
    array(
        'route' => $routeLang . '/screenshots' . $routeGET,
        'controller' => 'screenshots.php'
    ),
    
    'register'
    =>
    array(
        'route' => $routeLang . '/register' . $routeGET,
        'controller' => 'register.php'
    ),
    
    'register-referral'
    =>
    array(
        'route' => $routeLang . '/register/referral/[A-Za-z0-9-]{3,15}' . $routeGET,
        'controller' => 'register.php'
    ),
    
    'recover-password'
    =>
    array(
        'route' => $routeLang . '/recover-password' . $routeGET,
        'controller' => 'recover-password.php'
    ),
    
    'change-password'
    =>
    array(
        'route' => $routeLang . '/recover-password/key/[A-Za-z0-9-]{64,64}',
        'controller' => 'recover-password.php'
    ),
    
    'change-email'
    =>
    array(
        'route' => $routeLang . '/change-email/key/[A-Za-z0-9-]{64,64}',
        'controller' => 'change-email.php'
    ),
    
    'forum'
    =>
    array(
        'route' => $routeLang . '/forum' . $routeGET,
        'controller' => 'forum.php'
    ),
    
    'forum-cat'
    =>
    array(
        'route' => $routeLang . '/forum/[A-Za-z0-9-]{3,25}' . $routeGET,
        'controller' => 'forum.php'
    ),
    
    'forum-cat-topic'
    =>
    array(
        'route' => $routeLang . '/forum/[A-Za-z0-9-]{3,25}/[A-Za-z0-9-]{3,100}' . $routeGET,
        'controller' => 'forum.php'
    ),
    
    'forum-cat-topic-page'
    =>
    array(
        'route' => $routeLang . '/forum/[A-Za-z0-9-]{3,25}/[A-Za-z0-9-]{3,100}/page/[1-9][0-9]{0,3}' . $routeGET,
        'controller' => 'forum.php'
    ),
    
    'privacy-policy'
    =>
    array(
        'route' => $routeLang . '/privacy-policy' . $routeGET,
        'controller' => 'privacy-policy.php'
    ),
    
    'terms-and-conditions'
    =>
    array(
        'route' => $routeLang . '/terms-and-conditions' . $routeGET,
        'controller' => 'terms-and-conditions.php'
    ),
    
    'get-the-app'
    =>
    array(
        'route' => $routeLang . '/download-app' . $routeGET,
        'controller' => 'get-app.php'
    ),
    
    'link-partners'
    =>
    array(
        'route' => $routeLang . '/link-partners' . $routeGET,
        'controller' => 'link-partners.php'
    ),
    
    '_offline'
    =>
    array(
        'route' => $routeLang . '/offline.html',
        'controller' => 'offline.php'
    ),
    
    /*
    'php-info'
    =>
    array(
        'route' => '/php-info',
        'controller' => 'php.info.php'
    ),
    */
    
    /** Vanaf hier alle game routes **/
    'game'
    =>
    array(
        'route' => '/game',
        'controller' => 'game/news.php'
    ),
    
    'news'
    =>
    array(
        'route' => '/game/news',
        'controller' => 'game/news.php'
    ),
    
    'news-news'
    =>
    array(
        'route' => '/game/news/news',
        'controller' => 'game/news.php'
    ),
    
    'news-updates'
    =>
    array(
        'route' => '/game/news/updates',
        'controller' => 'game/news.php'
    ),
    
    'status'
    =>
    array(
        'route' => '/game/status',
        'controller' => 'game/status.php'
    ),
    
    'bank'
    =>
    array(
        'route' => '/game/bank',
        'controller' => 'game/bank.php'
    ),
    
    'swiss-bank'
    =>
    array(
        'route' => '/game/bank/swiss-bank',
        'controller' => 'game/bank.php'
    ),
    
    'financial'
    =>
    array(
        'route' => '/game/bank/financial',
        'controller' => 'game/bank.php'
    ),
    
    'bank-logs'
    =>
    array(
        'route' => '/game/bank/bank-logs',
        'controller' => 'game/bank.php'
    ),
    
    'prison'
    =>
    array(
        'route' => '/game/prison',
        'controller' => 'game/prison.php'
    ),
    
    'prison-page'
    =>
    array(
        'route' => '/game/prison/page/[1-9][0-9]{0,3}',
        'controller' => 'game/prison.php'
    ),
    
    'in_prison'
    =>
    array(
        'route' => '/game/in-prison/unable-to/[A-Za-z0-9-%]{3,25}',
        'controller' => 'game/prison.php'
    ),
    
    'in_prison_raw_paging'
    =>
    array(
        'route' => '/game/in-prison/unable-to',
        'controller' => 'game/prison.php'
    ),
    
    'in-prison-page'
    =>
    array(
        'route' => '/game/in-prison/unable-to/page/[1-9][0-9]{0,3}',
        'controller' => 'game/prison.php'
    ),
    
    'famprison'
    =>
    array(
        'route' => '/game/family-prison',
        'controller' => 'game/prison.php'
    ),
    
    'famprison-page'
    =>
    array(
        'route' => '/game/family-prison/page/[1-9][0-9]{0,3}',
        'controller' => 'game/prison.php'
    ),
    
    'honor-points'
    =>
    array(
        'route' => '/game/honor-points',
        'controller' => 'game/honor-points.php'
    ),
    
    'send-honor-points'
    =>
    array(
        'route' => '/game/send/honor-points',
        'controller' => 'game/honor-points.php'
    ),
    
    'travel-airplane'
    =>
    array(
        'route' => '/game/travel',
        'controller' => 'game/travel.php'
    ),
    
    'travel-train'
    =>
    array(
        'route' => '/game/travel/train',
        'controller' => 'game/travel.php'
    ),
    
    'travel-bus'
    =>
    array(
        'route' => '/game/travel/bus',
        'controller' => 'game/travel.php'
    ),
    
    'travel-vehicle'
    =>
    array(
        'route' => '/game/travel/vehicle',
        'controller' => 'game/travel.php'
    ),
    
    'travel-vehicle-id'
    =>
    array(
        'route' => '/game/travel/vehicle/[1-9][0-9]{0,7}',
        'controller' => 'game/travel.php'
    ),
    
    'market'
    =>
    array(
        'route' => '/game/market',
        'controller' => 'game/market.php'
    ),
    
    'market-whores'
    =>
    array(
        'route' => '/game/market/hoes',
        'controller' => 'game/market.php'
    ),
    
    'market-honor-points'
    =>
    array(
        'route' => '/game/market/honor-points',
        'controller' => 'game/market.php'
    ),
    
    'stock-exchange'
    =>
    array(
        'route' => '/game/stock-exchange',
        'controller' => 'game/stock-exchange.php'
    ),
    
    'stock-exchange-business'
    =>
    array(
        'route' => '/game/stock-exchange/business/[A-Za-z0-9-]{3,25}',
        'controller' => 'game/stock-exchange.php'
    ),
    
    'stock-exchange-news'
    =>
    array(
        'route' => '/game/stock-exchange/news',
        'controller' => 'game/stock-exchange.php'
    ),
    
    'stock-exchange-portfolio'
    =>
    array(
        'route' => '/game/stock-exchange/portfolio',
        'controller' => 'game/stock-exchange.php'
    ),
    
    'equipment-stores'
    =>
    array(
        'route' => '/game/equipment-stores',
        'controller' => 'game/equipment-stores.php'
    ),
    
    'equipment-stores-protection'
    =>
    array(
        'route' => '/game/equipment-stores/protection',
        'controller' => 'game/equipment-stores.php'
    ),
    
    'equipment-stores-airplanes'
    =>
    array(
        'route' => '/game/equipment-stores/airplanes',
        'controller' => 'game/equipment-stores.php'
    ),
    
    'estate-agency'
    =>
    array(
        'route' => '/game/estate-agency',
        'controller' => 'game/estate-agency.php'
    ),
    
    'bullet-factories'
    =>
    array(
        'route' => '/game/bullet-factories',
        'controller' => 'game/bullet-factories.php'
    ),
    
    'hitlist'
    =>
    array(
        'route' => '/game/hitlist',
        'controller' => 'game/hitlist.php'
    ),
    
    'murder'
    =>
    array(
        'route' => '/game/murder',
        'controller' => 'game/murder.php'
    ),
    
    'murder-user'
    =>
    array(
        'route' => '/game/murder/user/[A-Za-z0-9-]{3,25}',
        'controller' => 'game/murder.php'
    ),
    
    'murder-backfire'
    =>
    array(
        'route' => '/game/murder/backfire',
        'controller' => 'game/murder.php'
    ),
    
    'murder-detective'
    =>
    array(
        'route' => '/game/murder/detective',
        'controller' => 'game/murder.php'
    ),
    
    'murder-mercenaries'
    =>
    array(
        'route' => '/game/murder/mercenaries',
        'controller' => 'game/murder.php'
    ),
    
    'murder-weapon-training'
    =>
    array(
        'route' => '/game/murder/weapon-training',
        'controller' => 'game/murder.php'
    ),
    
    'weapon-training-do'
    =>
    array(
        'route' => '/game/murder/weapon-training/train-now',
        'controller' => 'game/murder.php'
    ),
    
    'murder-logs'
    =>
    array(
        'route' => '/game/murder/logs',
        'controller' => 'game/murder.php'
    ),
    
    'murder-logs-page'
    =>
    array(
        'route' => '/game/murder/logs/page/[1-9][0-9]{0,3}',
        'controller' => 'game/murder.php'
    ),
    
    'hospital'
    =>
    array(
        'route' => '/game/hospital',
        'controller' => 'game/hospital.php'
    ),
    
    'red-light-district'
    =>
    array(
        'route' => '/game/red-light-district',
        'controller' => 'game/red-light-district.php'
    ),
    
    'red-light-district-do'
    =>
    array(
        'route' => '/game/red-light-district/pimp-now',
        'controller' => 'game/red-light-district.php'
    ),
    
    'buy-hooker-windows'
    =>
    array(
        'route' => '/game/red-light-district/windows',
        'controller' => 'game/red-light-district.php'
    ),
    
    'gym'
    =>
    array(
        'route' => '/game/gym',
        'controller' => 'game/gym.php'
    ),
    
    'gym-do'
    =>
    array(
        'route' => '/game/gym/train-now',
        'controller' => 'game/gym.php'
    ),
    
    'ground-map'
    =>
    array(
        'route' => '/game/ground-map',
        'controller' => 'game/ground.php'
    ),
    
    'missions'
    =>
    array(
        'route' => '/game/missions',
        'controller' => 'game/missions.php'
    ),
    
    'missions-public-mission'
    =>
    array(
        'route' => '/game/missions/public',
        'controller' => 'game/missions.php'
    ),
    
    'daily-challenges'
    =>
    array(
        'route' => '/game/daily-challenges',
        'controller' => 'game/daily-challenges.php'
    ),
    
    'possessions'
    =>
    array(
        'route' => '/game/possessions',
        'controller' => 'game/possessions.php'
    ),
    
    'donation-shop'
    =>
    array(
        'route' => '/game/donation-shop',
        'controller' => 'game/donation-shop.php'
    ),
    
    'poll'
    =>
    array(
        'route' => '/game/poll',
        'controller' => 'game/poll.php'
    ),
    
    'poll-history'
    =>
    array(
        'route' => '/game/poll-history',
        'controller' => 'game/poll.php'
    ),
    
    'game-forum'
    =>
    array(
        'route' => '/game/forum',
        'controller' => 'game/forum.php'
    ),
    
    'game-forum-cat'
    =>
    array(
        'route' => '/game/forum/[A-Za-z0-9-]{3,25}',
        'controller' => 'game/forum.php'
    ),
    
    'game-forum-cat-topic'
    =>
    array(
        'route' => '/game/forum/[A-Za-z0-9-]{3,30}/[A-Za-z0-9-]{3,100}',
        'controller' => 'game/forum.php'
    ),
    
    'game-forum-cat-topic-page'
    =>
    array(
        'route' => '/game/forum/[A-Za-z0-9-]{3,30}/[A-Za-z0-9-]{3,100}/page/[1-9][0-9]{0,3}',
        'controller' => 'game/forum.php'
    ),
    
    /**
    'game-forum-cat-topic-action'
    =>
    array(
        'route' => '/game/forum/[A-Za-z0-9-]{3,30}/[A-Za-z0-9-]{3,100}/#\b(delete|report|edit|move)\b#',
        'controller' => 'game/forum.php'
    ),
    
    'game-forum-cat-topic-reaction-action'
    =>
    array(
        'route' => '/game/forum/[A-Za-z0-9-]{3,30}/[A-Za-z0-9-]{3,100}/[1-9][0-9]/#\b(delete|quote|report|edit|move)\b#',
        'controller' => 'game/forum.php'
    ),
    **/
    
    'shoutbox'
    =>
    array(
        'route' => '/game/shoutbox',
        'controller' => 'game/shoutbox.php'
    ),
    
    'shoutbox-page'
    =>
    array(
        'route' => '/game/shoutbox/page/[1-9][0-9]{0,3}',
        'controller' => 'game/shoutbox.php'
    ),
    
    'soccer-betting'
    =>
    array(
        'route' => '/game/soccer-betting',
        'controller' => 'game/soccer-betting.php'
    ),
    
    'fifty-games'
    =>
    array(
        'route' => '/game/fifty-games',
        'controller' => 'game/fifty-games.php'
    ),
    
    'fifty-games-whores'
    =>
    array(
        'route' => '/game/fifty-games/hoes',
        'controller' => 'game/fifty-games.php'
    ),
    
    'fifty-games-honor-points'
    =>
    array(
        'route' => '/game/fifty-games/honor-points',
        'controller' => 'game/fifty-games.php'
    ),
    
    'dobbling'
    =>
    array(
        'route' => '/game/dobbling',
        'controller' => 'game/dobbling.php'
    ),
    
    'racetrack'
    =>
    array(
        'route' => '/game/racetrack',
        'controller' => 'game/racetrack.php'
    ),
    
    'roulette'
    =>
    array(
        'route' => '/game/roulette',
        'controller' => 'game/roulette.php'
    ),
    
    'slot-machine'
    =>
    array(
        'route' => '/game/slot-machine',
        'controller' => 'game/slot-machine.php'
    ),
    
    'blackjack'
    =>
    array(
        'route' => '/game/blackjack',
        'controller' => 'game/blackjack.php'
    ),
    
    'lottery'
    =>
    array(
        'route' => '/game/lottery',
        'controller' => 'game/lottery.php'
    ),
    
    'lottery-week'
    =>
    array(
        'route' => '/game/lottery/weekly-superpot',
        'controller' => 'game/lottery.php'
    ),
    
    'profile'
    =>
    array(
        'route' => '/game/profile/[A-Za-z0-9-]{3,25}',
        'controller' => 'game/profile.php'
    ),
    
    'profile-pimp-now'
    =>
    array(
        'route' => '/game/profile/[A-Za-z0-9-]{3,25}/pimp-for-player',
        'controller' => 'game/profile.php'
    ),
    
    'crimes'
    =>
    array(
        'route' => '/game/crimes',
        'controller' => 'game/crimes.php'
    ),
    
    'crimes-do'
    =>
    array(
        'route' => '/game/crimes/commit-crime-now',
        'controller' => 'game/crimes.php'
    ),
    
    'organized-crimes'
    =>
    array(
        'route' => '/game/organized-crimes',
        'controller' => 'game/crimes.php'
    ),
    
    'steal-vehicles'
    =>
    array(
        'route' => '/game/steal-vehicles',
        'controller' => 'game/steal-vehicles.php'
    ),
    
    'steal-vehicles-do'
    =>
    array(
        'route' => '/game/steal-vehicles/steal-vehicle-now',
        'controller' => 'game/steal-vehicles.php'
    ),
    
    'drugs-liquids'
    =>
    array(
        'route' => '/game/drugs-liquids',
        'controller' => 'game/drugs-liquids.php'
    ),
    
    'drugs-liquids-liquids'
    =>
    array(
        'route' => '/game/drugs-liquids/liquids',
        'controller' => 'game/drugs-liquids.php'
    ),
    
    'smuggling'
    =>
    array(
        'route' => '/game/smuggling',
        'controller' => 'game/smuggling.php'
    ),
    
    'smuggling-liquids'
    =>
    array(
        'route' => '/game/smuggling/liquids',
        'controller' => 'game/smuggling.php'
    ),
    
    'smuggling-fireworks'
    =>
    array(
        'route' => '/game/smuggling/fireworks',
        'controller' => 'game/smuggling.php'
    ),
    
    'smuggling-weapons'
    =>
    array(
        'route' => '/game/smuggling/weapons',
        'controller' => 'game/smuggling.php'
    ),
    
    'smuggling-exotic-animals'
    =>
    array(
        'route' => '/game/smuggling/exotic-animals',
        'controller' => 'game/smuggling.php'
    ),
    
    'smuggling-profit-index'
    =>
    array(
        'route' => '/game/smuggling/profit-index',
        'controller' => 'game/smuggling.php'
    ),
    
    'smuggling-profit-index-unit'
    =>
    array(
        'route' => '/game/smuggling/profit-index/[1-9][0-9]{0,3}',
        'controller' => 'game/smuggling.php'
    ),
    
    'garage'
    =>
    array(
        'route' => '/game/garage',
        'controller' => 'game/garage.php'
    ),
    
    'garage-page'
    =>
    array(
        'route' => '/game/garage/page/[1-9][0-9]{0,3}',
        'controller' => 'game/garage.php'
    ),
    
    'garage-shop'
    =>
    array(
        'route' => '/game/garage/vehicle-shop',
        'controller' => 'game/garage.php'
    ),
    
    'garage-shop-page'
    =>
    array(
        'route' => '/game/garage/vehicle-shop/page/[1-9][0-9]{0,3}',
        'controller' => 'game/garage.php'
    ),
    
    'garage-shop-vehicle'
    =>
    array(
        'route' => '/game/garage/vehicle-shop/more-info/vehicle/[1-9][0-9]{0,3}',
        'controller' => 'game/garage.php'
    ),
    
    'garage-shop-vehicle-raw'
    =>
    array(
        'route' => '/game/garage/vehicle-shop/more-info/vehicle/',
        'controller' => 'game/garage.php'
    ),
    
    'streetrace'
    =>
    array(
        'route' => '/game/streetrace',
        'controller' => 'game/streetrace.php'
    ),
    
    'family-list'
    =>
    array(
        'route' => '/game/family-list',
        'controller' => 'game/family-list.php'
    ),
    
    'family-page'
    =>
    array(
        'route' => '/game/family-page/[A-Za-z0-9-]{3,25}',
        'controller' => 'game/family-page.php'
    ),
    
    'family-bank'
    =>
    array(
        'route' => '/game/family-bank',
        'controller' => 'game/family-bank.php'
    ),
    
    'family-bank-manage'
    =>
    array(
        'route' => '/game/family-bank/manage',
        'controller' => 'game/family-bank.php'
    ),
    
    'family-bank-manage-page'
    =>
    array(
        'route' => '/game/family-bank/manage/page/[1-9][0-9]{0,3}',
        'controller' => 'game/family-bank.php'
    ),
    
    'family-shoutbox'
    =>
    array(
        'route' => '/game/shoutbox/family',
        'controller' => 'game/shoutbox.php'
    ),
    
    'family-shoutbox-page'
    =>
    array(
        'route' => '/game/shoutbox/family/page/[1-9][0-9]{0,3}',
        'controller' => 'game/shoutbox.php'
    ),
    
    'family-garage'
    =>
    array(
        'route' => '/game/family-garage',
        'controller' => 'game/family-garage.php'
    ),
    
    'family-garage-page'
    =>
    array(
        'route' => '/game/family-garage/page/[1-9][0-9]{0,3}',
        'controller' => 'game/family-garage.php'
    ),
    
    'family-garage-crusher-converter'
    =>
    array(
        'route' => '/game/family-garage/crusher-converter',
        'controller' => 'game/family-garage.php'
    ),
    
    'family-properties'
    =>
    array(
        'route' => '/game/family-properties',
        'controller' => 'game/family-properties.php'
    ),
    
    'family-properties-page'
    =>
    array(
        'route' => '/game/family-properties/page/[1-9][0-9]{0,3}',
        'controller' => 'game/family-properties.php'
    ),
    
    'family-properties-brothel'
    =>
    array(
        'route' => "/game/family-properties/brothel",
        'controller' => 'game/family-properties.php'
    ),
    
    'family-crimes'
    =>
    array(
        'route' => '/game/family-crimes',
        'controller' => 'game/family-crimes.php'
    ),
    
    'family-raid'
    =>
    array(
        'route' => '/game/family-raid',
        'controller' => 'game/family-raid.php'
    ),
    
    'family-missions'
    =>
    array(
        'route' => '/game/family-missions',
        'controller' => 'game/family-missions.php'
    ),
    
    'family-history'
    =>
    array(
        'route' => '/game/family-history',
        'controller' => 'game/family-history.php'
    ),
    
    'family-history-page'
    =>
    array(
        'route' => '/game/family-history/page/[1-9][0-9]{0,3}',
        'controller' => 'game/family-history.php'
    ),
    
    'family-management'
    =>
    array(
        'route' => '/game/family-management',
        'controller' => 'game/family-management.php'
    ),
    
    'family-management-profile'
    =>
    array(
        'route' => '/game/family-management/profile',
        'controller' => 'game/family-management.php'
    ),
    
    'family-management-mass-message'
    =>
    array(
        'route' => '/game/family-management/mass-message',
        'controller' => 'game/family-management.php'
    ),
    
    'family-management-message'
    =>
    array(
        'route' => '/game/family-management/message',
        'controller' => 'game/family-management.php'
    ),
    
    'family-management-alliances'
    =>
    array(
        'route' => '/game/family-management/alliances',
        'controller' => 'game/family-management.php'
    ),
    
    'family-management-manage-family'
    =>
    array(
        'route' => '/game/family-management/manage-family',
        'controller' => 'game/family-management.php'
    ),
    
    'family-invitations'
    =>
    array(
        'route' => '/game/family-invitations',
        'controller' => 'game/family-invitations.php'
    ),
    
    'create-family'
    =>
    array(
        'route' => '/game/create-family',
        'controller' => 'game/family-create.php'
    ),
    
    'family-message'
    =>
    array(
        'route' => '/game/family-message',
        'controller' => 'game/family-message.php'
    ),
    
    'family-mercenaries'
    =>
    array(
        'route' => '/game/family-mercenaries',
        'controller' => 'game/family-mercenaries.php'
    ),
    
    'family-mercenaries-page'
    =>
    array(
        'route' => '/game/family-mercenaries/page/[1-9][0-9]{0,3}',
        'controller' => 'game/family-mercenaries.php'
    ),
    
    'share-mafiasource'
    =>
    array(
        'route' => '/game/share-mafiasource',
        'controller' => 'game/share-mafiasource.php'
    ),
    
    'members'
    =>
    array(
        'route' => '/game/members',
        'controller' => 'game/members.php'
    ),
    
    'toplist'
    =>
    array(
        'route' => '/game/toplist',
        'controller' => 'game/toplist.php'
    ),
    
    'toplist-page'
    =>
    array(
        'route' => '/game/toplist/page/[1-9][0-9]{0,3}',
        'controller' => 'game/toplist.php'
    ),
    
    'toplist-list'
    =>
    array(
        'route' => '/game/toplist/list',
        'controller' => 'game/toplist.php'
    ),
    
    'toplist-list-page'
    =>
    array(
        'route' => '/game/toplist/list/page/[1-9][0-9]{0,3}',
        'controller' => 'game/toplist.php'
    ),
    
    'information'
    =>
    array(
        'route' => '/game/information',
        'controller' => 'game/information.php'
    ),
    
    'information-rules'
    =>
    array(
        'route' => '/game/information/rules',
        'controller' => 'game/information.php'
    ),
    
    'information-team-members'
    =>
    array(
        'route' => '/game/information/team-members',
        'controller' => 'game/information.php'
    ),
    
    'information-hall-of-fame'
    =>
    array(
        'route' => '/game/information/hall-of-fame',
        'controller' => 'game/information.php'
    ),
    
    'information-hall-of-fame-round'
    =>
    array(
        'route' => '/game/information/hall-of-fame/[0-9][0-9]{0,3}',
        'controller' => 'game/information.php'
    ),
    
    'logout'
    =>
    array(
        'route' => '/game/logout',
        'controller' => 'game/logout.php'
    ),
    
    'ranks-score'
    =>
    array(
        'route' => '/game/ranks-score',
        'controller' => 'game/ranks-score.php'
    ),
    
    'unknown_ip'
    =>
    array(
        'route' => '/game/unknown-ip',
        'controller' => 'game/unknown-ip.php'
    ),
    
    'captcha_test'
    =>
    array(
        'route' => '/game/captcha-test',
        'controller' => 'game/captcha.test.php'
    ),
    
    'rest_in_peace'
    =>
    array(
        'route' => '/game/rest-in-peace',
        'controller' => "game/rest.in.peace.php"
    ),
    
    'click-mission'
    => 
    array(
        'route' => '/click-mission',
        'controller' => 'game/click-mission.php'
    ),
    
    'not_found'
    => 
    array(
        'route' => '/404',
        'controller' => 'notfound.php'
    ),
    
    'not_found_ssl'
    => 
    array(
        'route' => '/404.shtml',
        'controller' => 'notfound.php'
    ),
    
    /*
    ''
    =>
    array(
    'route' => '/game/',
    'controller' => 'game/.php'
    ),
    */

    //Voorbeeld 1: Werken met pagina nummers
  	    //'news_page'
    //=>
    //array(
    // 'route' => '/news/page/[1-9][0-9]{0,3}',
    // 'controller' => 'news.php'
    //),

    //Voorbeeld 2: Werken met pagina titels
    //'news_article'
    //=>
    //array(
    // 'route' => '/news/article/[A-Za-z0-9-]{3,200}',
    // 'controller' => 'news_article.php'
    //)
    
    /** 
     * Admin CP routes
     * **/
    
    'admin'
    =>
    array(
        'route' => '/admin',
        'controller' => 'admin/index.php'
    ),
    
    'admin-cms'
    =>
    array(
        'route' => '/admin/cms',
        'controller' => 'admin/cms.php'
    ),
    
    'admin-cms-pagina'
    =>
    array(
        'route' => '/admin/cms/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/cms.php'
    ),
    
    'admin-login'
    =>
    array(
        'route' => '/admin/login',
        'controller' => 'admin/login.php'
    ),
    
    'admin-logout'
    =>
    array(
        'route' => '/admin/logout',
        'controller' => 'admin/logout.php'
    ),
    
    'admin-account-settings'
    =>
    array(
        'route' => '/admin/account-settings',
        'controller' => 'admin/account-settings.php'
    ),
    
    'admin-create-member'
    =>
    array(
        'route' => '/admin/create-member',
        'controller' => 'admin/create-member.php'
    ),
    
    'admin-members'
    =>
    array(
        'route' => '/admin/gebruikers',
        'controller' => 'admin/members.php'
    ),
    
    'admin-members-pagina'
    =>
    array(
        'route' => '/admin/gebruikers/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/members.php'
    ),
    
    'admin-users'
    =>
    array(
        'route' => '/admin/users',
        'controller' => 'admin/users.php'
    ),
    
    'admin-users-pagina'
    =>
    array(
        'route' => '/admin/users/pagina/[1-9][0-9]{0,6}',
        'controller' => 'admin/users.php'
    ),
    
    'admin-member-status'
    =>
    array(
        'route' => '/admin/member-statussen',
        'controller' => 'admin/status.php'
    ),
    
    'admin-member-status-pagina'
    =>
    array(
        'route' => '/admin/member-statussen/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/status.php'
    ),
    
    'admin-users-donateur'
    =>
    array(
        'route' => '/admin/donateur',
        'controller' => 'admin/donateur.php'
    ),
    
    'admin-users-donateur-pagina'
    =>
    array(
        'route' => '/admin/donateur/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/donateur.php'
    ),
    
    'admin-beroep'
    =>
    array(
        'route' => '/admin/beroepen',
        'controller' => 'admin/beroep.php'
    ),
    
    'admin-beroep-pagina'
    =>
    array(
        'route' => '/admin/beroepen/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/beroep.php'
    ),
    
    'admin-news'
    =>
    array(
        'route' => '/admin/news',
        'controller' => 'admin/news.php'
    ),
    
    'admin-news-pagina'
    =>
    array(
        'route' => '/admin/news/pagina/[1-9][0-9]{0,6}',
        'controller' => 'admin/news.php'
    ),
    
    'admin-states'
    =>
    array(
        'route' => '/admin/states',
        'controller' => 'admin/states.php'
    ),
    
    'admin-states-pagina'
    =>
    array(
        'route' => '/admin/states/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/states.php'
    ),
    
    'admin-cities'
    =>
    array(
        'route' => '/admin/cities',
        'controller' => 'admin/cities.php'
    ),
    
    'admin-cities-pagina'
    =>
    array(
        'route' => '/admin/cities/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/cities.php'
    ),
    
    'admin-ground-map'
    =>
    array(
        'route' => '/admin/ground-map',
        'controller' => 'admin/ground.php'
    ),
    
    'admin-ground-map-pagina'
    =>
    array(
        'route' => '/admin/ground-map/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/ground.php'
    ),
    
    'admin-ground-buildings'
    =>
    array(
        'route' => '/admin/ground-buildings',
        'controller' => 'admin/ground-buildings.php'
    ),
    
    'admin-ground-buildings-pagina'
    =>
    array(
        'route' => '/admin/ground-buildings/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/ground-buildings.php'
    ),
    
    'admin-whores-rld'
    =>
    array(
        'route' => '/admin/whores-rld',
        'controller' => 'admin/whores-rld.php'
    ),
    
    'admin-whores-rld-pagina'
    =>
    array(
        'route' => '/admin/whores-rld/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/whores-rld.php'
    ),
    
    'admin-helpsystem'
    =>
    array(
        'route' => '/admin/helpsystem',
        'controller' => 'admin/helpsystem.php'
    ),
    
    'admin-helpsystem-pagina'
    =>
    array(
        'route' => '/admin/helpsystem/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/helpsystem.php'
    ),
    
    'admin-vehicles'
    =>
    array(
        'route' => '/admin/vehicles',
        'controller' => 'admin/vehicles.php'
    ),
    
    'admin-vehicles-pagina'
    =>
    array(
        'route' => '/admin/vehicles/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/vehicles.php'
    ),
    
    'admin-residences'
    =>
    array(
        'route' => '/admin/residences',
        'controller' => 'admin/residences.php'
    ),
    
    'admin-residences-pagina'
    =>
    array(
        'route' => '/admin/residences/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/residences.php'
    ),
    
    'admin-crimes'
    =>
    array(
        'route' => '/admin/crimes',
        'controller' => 'admin/crimes.php'
    ),
    
    'admin-crimes-pagina'
    =>
    array(
        'route' => '/admin/crimes/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/crimes.php'
    ),
    
    'admin-crimes-org'
    =>
    array(
        'route' => '/admin/crimes-org',
        'controller' => 'admin/crimes-org.php'
    ),
    
    'admin-crimes-org-pagina'
    =>
    array(
        'route' => '/admin/crimes-org/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/crimes-org.php'
    ),
    
    'admin-steal-vehicles'
    =>
    array(
        'route' => '/admin/steal-vehicles',
        'controller' => 'admin/steal-vehicles.php'
    ),
    
    'admin-steal-vehicles-pagina'
    =>
    array(
        'route' => '/admin/steal-vehicles/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/steal-vehicles.php'
    ),
    
    'admin-possess'
    =>
    array(
        'route' => '/admin/possess',
        'controller' => 'admin/possess.php'
    ),
    
    'admin-possess-pagina'
    =>
    array(
        'route' => '/admin/possess/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/possess.php'
    ),
    
    'admin-possessions'
    =>
    array(
        'route' => '/admin/possessions',
        'controller' => 'admin/possessions.php'
    ),
    
    'admin-possessions-pagina'
    =>
    array(
        'route' => '/admin/possessions/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/possessions.php'
    ),
    
    'admin-forum-categories'
    =>
    array(
        'route' => '/admin/forum-categories',
        'controller' => 'admin/forum-categories.php'
    ),
    
    'admin-forum-categories-pagina'
    =>
    array(
        'route' => '/admin/forum-categories/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/forum-categories.php'
    ),
    
    'admin-forum-statuses'
    =>
    array(
        'route' => '/admin/forum-statuses',
        'controller' => 'admin/forum-statuses.php'
    ),
    
    'admin-forum-statuses-pagina'
    =>
    array(
        'route' => '/admin/forum-statuses/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/forum-statuses.php'
    ),
    
    'admin-forum-topics'
    =>
    array(
        'route' => '/admin/forum-topics',
        'controller' => 'admin/forum-topics.php'
    ),
    
    'admin-forum-topics-pagina'
    =>
    array(
        'route' => '/admin/forum-topics/pagina/[1-9][0-9]{0,6}',
        'controller' => 'admin/forum-topics.php'
    ),
    
    'admin-forum-reactions'
    =>
    array(
        'route' => '/admin/forum-reactions',
        'controller' => 'admin/forum-reactions.php'
    ),
    
    'admin-forum-reactions-pagina'
    =>
    array(
        'route' => '/admin/forum-reactions/pagina/[1-9][0-9]{0,6}',
        'controller' => 'admin/forum-reactions.php'
    ),
    
    'admin-smuggling'
    =>
    array(
        'route' => '/admin/smuggling',
        'controller' => 'admin/smuggling.php'
    ),
    
    'admin-smuggling-pagina'
    =>
    array(
        'route' => '/admin/smuggling/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/smuggling.php'
    ),
    
    'admin-weapons'
    =>
    array(
        'route' => '/admin/weapons',
        'controller' => 'admin/weapons.php'
    ),
    
    'admin-weapons-pagina'
    =>
    array(
        'route' => '/admin/weapons/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/weapons.php'
    ),
    
    'admin-airplanes'
    =>
    array(
        'route' => '/admin/airplanes',
        'controller' => 'admin/airplanes.php'
    ),
    
    'admin-airplanes-pagina'
    =>
    array(
        'route' => '/admin/airplanes/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/airplanes.php'
    ),
    
    'admin-protection'
    =>
    array(
        'route' => '/admin/protection',
        'controller' => 'admin/protection.php'
    ),
    
    'admin-protection-pagina'
    =>
    array(
        'route' => '/admin/protection/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/protection.php'
    ),
    
    'admin-gym-competition'
    =>
    array(
        'route' => '/admin/gym-competition',
        'controller' => 'admin/gym-competition.php'
    ),
    
    'admin-gym-competition-pagina'
    =>
    array(
        'route' => '/admin/gym-competition/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/gym-competition.php'
    ),
    
    'admin-families'
    =>
    array(
        'route' => '/admin/families',
        'controller' => 'admin/families.php'
    ),
    
    'admin-families-pagina'
    =>
    array(
        'route' => '/admin/families/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/families.php'
    ),
    
    'admin-detectives'
    =>
    array(
        'route' => '/admin/detectives',
        'controller' => 'admin/detectives.php'
    ),
    
    'admin-detectives-pagina'
    =>
    array(
        'route' => '/admin/detectives/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/detectives.php'
    ),
    
    'admin-family-alliances'
    =>
    array(
        'route' => '/admin/family-alliances',
        'controller' => 'admin/family-alliances.php'
    ),
    
    'admin-family-alliances-pagina'
    =>
    array(
        'route' => '/admin/family-alliances/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/family-alliances.php'
    ),
    
    'admin-family-bullet-donations'
    =>
    array(
        'route' => '/admin/family-bullet-donations',
        'controller' => 'admin/family-bullet-donations.php'
    ),
    
    'admin-family-bullet-donations-pagina'
    =>
    array(
        'route' => '/admin/family-bullet-donations/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/family-bullet-donations.php'
    ),
    
    'admin-family-bullets-sent'
    =>
    array(
        'route' => '/admin/family-bullets-sent',
        'controller' => 'admin/family-bullets-sent.php'
    ),
    
    'admin-family-bullets-sent-pagina'
    =>
    array(
        'route' => '/admin/family-bullets-sent/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/family-bullets-sent.php'
    ),
    
    'admin-family-brothel-whores'
    =>
    array(
        'route' => '/admin/family-brothel-whores',
        'controller' => 'admin/family-brothel-whores.php'
    ),
    
    'admin-family-brothel-whores-pagina'
    =>
    array(
        'route' => '/admin/family-brothel-whores/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/family-brothel-whores.php'
    ),
    
    'admin-family-garage'
    =>
    array(
        'route' => '/admin/family-garage',
        'controller' => 'admin/family-garage.php'
    ),
    
    'admin-family-garage-pagina'
    =>
    array(
        'route' => '/admin/family-garage/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/family-garage.php'
    ),
    
    'admin-family-join-invite'
    =>
    array(
        'route' => '/admin/family-join-invite',
        'controller' => 'admin/family-join-invite.php'
    ),
    
    'admin-family-join-invite-pagina'
    =>
    array(
        'route' => '/admin/family-join-invite/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/family-join-invite.php'
    ),
    
    'admin-family-mercenaries'
    =>
    array(
        'route' => '/admin/family-mercenaries',
        'controller' => 'admin/family-mercenaries.php'
    ),
    
    'admin-family-mercenaries-pagina'
    =>
    array(
        'route' => '/admin/family-mercenaries/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/family-mercenaries.php'
    ),
    
    'admin-family-raid'
    =>
    array(
        'route' => '/admin/family-raid',
        'controller' => 'admin/family-raid.php'
    ),
    
    'admin-family-raid-pagina'
    =>
    array(
        'route' => '/admin/family-raid/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/family-raid.php'
    ),
    
    'admin-garage'
    =>
    array(
        'route' => '/admin/garage',
        'controller' => 'admin/garage.php'
    ),
    
    'admin-garage-pagina'
    =>
    array(
        'route' => '/admin/garage/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/garage.php'
    ),
    
    'admin-user-garage'
    =>
    array(
        'route' => '/admin/user-garage',
        'controller' => 'admin/user-garage.php'
    ),
    
    'admin-user-garage-pagina'
    =>
    array(
        'route' => '/admin/user-garage/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/user-garage.php'
    ),
    
    'admin-market'
    =>
    array(
        'route' => '/admin/market',
        'controller' => 'admin/market.php'
    ),
    
    'admin-market-pagina'
    =>
    array(
        'route' => '/admin/market/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/market.php'
    ),
    
    'admin-messages'
    =>
    array(
        'route' => '/admin/messages',
        'controller' => 'admin/messages.php'
    ),
    
    'admin-messages-pagina'
    =>
    array(
        'route' => '/admin/messages/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/messages.php'
    ),
    
    'admin-murder-log'
    =>
    array(
        'route' => '/admin/murder-log',
        'controller' => 'admin/murder-log.php'
    ),
    
    'admin-murder-log-pagina'
    =>
    array(
        'route' => '/admin/murder-log/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/murder-log.php'
    ),
    
    'admin-poll-questions'
    =>
    array(
        'route' => '/admin/poll-questions',
        'controller' => 'admin/poll-questions.php'
    ),
    
    'admin-poll-questions-pagina'
    =>
    array(
        'route' => '/admin/poll-questions/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/poll-questions.php'
    ),
    
    'admin-poll-answers'
    =>
    array(
        'route' => '/admin/poll-answers',
        'controller' => 'admin/poll-answers.php'
    ),
    
    'admin-poll-answers-pagina'
    =>
    array(
        'route' => '/admin/poll-answers/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/poll-answers.php'
    ),
    
    'admin-poll-votes'
    =>
    array(
        'route' => '/admin/poll-votes',
        'controller' => 'admin/poll-votes.php'
    ),
    
    'admin-poll-votes-pagina'
    =>
    array(
        'route' => '/admin/poll-votes/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/poll-votes.php'
    ),
    
    'admin-seo'
    =>
    array(
        'route' => '/admin/seo',
        'controller' => 'admin/seo.php'
    ),
    
    'admin-seo-pagina'
    =>
    array(
        'route' => '/admin/seo/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/seo.php'
    ),
    
    'admin-shoutbox-nl'
    =>
    array(
        'route' => '/admin/shoutbox-nl',
        'controller' => 'admin/shoutbox-nl.php'
    ),
    
    'admin-shoutbox-nl-pagina'
    =>
    array(
        'route' => '/admin/shoutbox-nl/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/shoutbox-nl.php'
    ),
    
    'admin-shoutbox-en'
    =>
    array(
        'route' => '/admin/shoutbox-en',
        'controller' => 'admin/shoutbox-en.php'
    ),
    
    'admin-shoutbox-en-pagina'
    =>
    array(
        'route' => '/admin/shoutbox-en/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/shoutbox-en.php'
    ),
    
    'admin-user-captcha'
    =>
    array(
        'route' => '/admin/user-captcha',
        'controller' => 'admin/user-captcha.php'
    ),
    
    'admin-user-captcha-pagina'
    =>
    array(
        'route' => '/admin/user-captcha/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/user-captcha.php'
    ),
    
    'admin-user-friend-block'
    =>
    array(
        'route' => '/admin/user-friend-block',
        'controller' => 'admin/user-friend-block.php'
    ),
    
    'admin-user-friend-block-pagina'
    =>
    array(
        'route' => '/admin/user-friend-block/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/user-friend-block.php'
    ),
    
    'admin-round'
    =>
    array(
        'route' => '/admin/round',
        'controller' => 'admin/round.php'
    ),
    
    'admin-round-pagina'
    =>
    array(
        'route' => '/admin/round/pagina/[1-9][0-9]{0,3}',
        'controller' => 'admin/round.php'
    ),
    
    'admin-reset'
    =>
    array(
        'route' => '/admin/reset',
        'controller' => 'admin/reset.php'
    ),
    
    /**
     * //END Admin CP routes
     * **/
); // //END all application routes

/** Next! Fetch AJAX Routes, also needed with it's plain $ajaxRoutes variable name in the routing class! **/
require_once __DIR__.'/ajax.routes.php';
