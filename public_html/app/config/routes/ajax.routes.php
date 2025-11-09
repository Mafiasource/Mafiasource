<?PHP

/** 
 * Define all possible ajax routes in this file
 * 
 * @RouteName = Route name used to redirect or simply build an URL, should remain unchanged
 * @RoutePath = The end-route URL visible in a users browser.
 * @Controller = PHP Controller to call whenever certain route is visited. (/public_html/src/Controllers)
 * 
 **/
 
/** AJAX ROUTES! All possible ajax routes > controllers below: **/

$ajaxRoutes =
array(
    /*
    'debug-me'
    =>
    array(
        'route' => '/game/debug',
        'controller' => 'Ajax/.debug.me.php'
    ),
    */
    'cookies-accept'
    =>
    array(
        'route' => '/accept-cookies',
        'controller' => 'Ajax/cookies-accept.php'
    ),
    
    'bank-donate'
    =>
    array(
        'route' => '/game/bank/donate-money',
        'controller' => 'Ajax/bank.donate-money.php'
    ),
    
    'bank-transfer'
    =>
    array(
        'route' => '/game/bank/transfer-money',
        'controller' => 'Ajax/bank.transfer-money.php'
    ),
    
    'swiss-bank-transfer'
    =>
    array(
        'route' => '/game/bank/transfer-swiss-money',
        'controller' => 'Ajax/bank.transfer-swiss-money.php'
    ),
    
    'shoutbox-post'
    =>
    array(
        'route' => '/game/shoutbox/post',
        'controller' => 'Ajax/shoutbox.post.php'
    ),
    
    'shoutbox-refresh'
    =>
    array(
        'route' => '/game/shoutbox/refresh',
        'controller' => 'Ajax/shoutbox.refresh.php'
    ),
    
    'shoutbox-check'
    =>
    array(
        'route' => '/game/shoutbox/check',
        'controller' => 'Ajax/shoutbox.check.php'
    ),
    
    'popup-tab'
    =>
    array(
        'route' => '/game/open-tab/[a-z0-9.]{3,25}',
        'controller' => 'Ajax/popup.open.tab.php'
    ),
    
    'save-settings'
    =>
    array(
        'route' => '/game/settings/save',
        'controller' => 'Ajax/account.save.settings.php'
    ),
    
    'reply-message'
    =>
    array(
        'route' => '/game/messages/reply',
        'controller' => 'Ajax/message.reply.php'
    ),
    
    'reload-message'
    =>
    array(
        'route' => '/game/messages/reload',
        'controller' => 'Ajax/message.reload.php'
    ),
    
    'check-message'
    =>
    array(
        'route' => '/game/messages/check',
        'controller' => 'Ajax/message.check.php'
    ),
    
    'travel-state-select'
    =>
    array(
        'route' => '/game/travel/city-select',
        'controller' => 'Ajax/travel.city.select.php'
    ),
    
    'travel'
    =>
    array(
        'route' => '/game/travel/go',
        'controller' => 'Ajax/travel.php'
    ),
    
    'prison-action'
    =>
    array(
        'route' => '/game/prison/commit-action',
        'controller' => 'Ajax/prison.action.php'
    ),
    
    'prison-refresh'
    =>
    array(
        'route' => '/game/prison/refresh',
        'controller' => 'Ajax/prison.refresh.php'
    ),
    
    'interact-stock'
    =>
    array(
        'route' => '/game/stock-exchange/interact-stock',
        'controller' => 'Ajax/stock.interact.php'
    ),
    
    'exchange-hp'
    =>
    array(
        'route' => '/game/honor-points/exchange',
        'controller' => 'Ajax/honorpoints.exchange.php'
    ),
    
    'send-hp'
    =>
    array(
        'route' => '/game/honor-points/send',
        'controller' => 'Ajax/honorpoints.send.php'
    ),
    
    'commit-crime'
    =>
    array(
        'route' => '/game/crimes/commit',
        'controller' => 'Ajax/crimes.commit.php'
    ),
    
    'prepare-organized-crime'
    =>
    array(
        'route' => '/game/organized-crimes/prepare',
        'controller' => 'Ajax/crimes.organized.prepare.php'
    ),
    
    'commit-organized-crime'
    =>
    array(
        'route' => '/game/organized-crimes/commit',
        'controller' => 'Ajax/crimes.organized.commit.php'
    ),
    
    'steal-vehicle'
    =>
    array(
        'route' => '/game/steal-vehicles/steal-vehicle',
        'controller' => 'Ajax/steal.vehicle.steal.php'
    ),
    
    'sell-vehicle'
    =>
    array(
        'route' => '/game/steal-vehicles/sell-vehicle',
        'controller' => 'Ajax/steal.vehicle.sell.php'
    ),
    
    'store-vehicle'
    =>
    array(
        'route' => '/game/steal-vehicles/store-vehicle',
        'controller' => 'Ajax/steal.vehicle.store.php'
    ),
    
    'possessions-state-select'
    =>
    array(
        'route' => '/game/possessions/location-select',
        'controller' => 'Ajax/possession.state.select.php'
    ),
    
    'interact-possession'
    =>
    array(
        'route' => '/game/possession/interact',
        'controller' => 'Ajax/possession.interact.php'
    ),
    
    'drop-possession'
    =>
    array(
        'route' => '/game/possession/drop',
        'controller' => 'Ajax/possession.drop.php'
    ),
    
    'search-player'
    =>
    array(
        'route' => '/game/members/search-player',
        'controller' => 'Ajax/user.search.php'
    ),
    
    'search-player-rank'
    =>
    array(
        'route' => '/game/members/search-player-rank',
        'controller' => 'Ajax/user.search.php'
    ),
    
    'create-family-AJAX'
    =>
    array(
        'route' => '/game/create-family/create',
        'controller' => 'Ajax/family.create.php'
    ),
    
    'search-family'
    =>
    array(
        'route' => '/game/families/search-family',
        'controller' => 'Ajax/family.search.php'
    ),
    
    'join-family'
    =>
    array(
        'route' => '/game/family-list/join-family',
        'controller' => 'Ajax/family.join.php'
    ),
    
    'leave-family'
    =>
    array(
        'route' => '/game/family-list/leave-family',
        'controller' => 'Ajax/family.leave.php'
    ),
    
    'donate-family'
    =>
    array(
        'route' => '/game/family-bank/donate',
        'controller' => 'Ajax/family.donate.php'
    ),
    
    'bank-transfer-family'
    =>
    array(
        'route' => '/game/family-bank/transfer',
        'controller' => 'Ajax/family.bank.transfer.php'
    ),
    
    'handle-family-join'
    =>
    array(
        'route' => '/game/family-management/handle-joined-member',
        'controller' => 'Ajax/family.handle.join.php'
    ),
    
    'handle-family-invite'
    =>
    array(
        'route' => '/game/family-management/handle-invited-member',
        'controller' => 'Ajax/family.handle.invite.php'
    ),
    
    'handle-family-invitation'
    =>
    array(
        'route' => '/game/family-invitations/handle-invitation',
        'controller' => 'Ajax/family.handle.invitation.php'
    ),
    
    'kick-family-member'
    =>
    array(
        'route' => '/game/family-management/kick-member',
        'controller' => 'Ajax/family.kick.member.php'
    ),
    
    'invite-family-member'
    =>
    array(
        'route' => '/game/family-management/invite-member',
        'controller' => 'Ajax/family.invite.member.php'
    ),
    
    'family-manage-join-policy'
    =>
    array(
        'route' => '/game/family-management/change-join-policy',
        'controller' => 'Ajax/family.change.joinpolicy.php'
    ),
    
    'upload-family-image'
    =>
    array(
        'route' => '/game/family-management/upload-family-image',
        'controller' => 'Ajax/family.upload.image.php'
    ),
    
    'upload-family-icon'
    =>
    array(
        'route' => '/game/family-management/upload-family-icon',
        'controller' => 'Ajax/family.upload.icon.php'
    ),
    
    'update-family-profile'
    =>
    array(
        'route' => '/game/family-management/update-family-profile',
        'controller' => 'Ajax/family.update.profile.php'
    ),
    
    'update-family-message'
    =>
    array(
        'route' => '/game/family-management/update-family-message',
        'controller' => 'Ajax/family.update.message.php'
    ),
    
    'manage-family-top'
    =>
    array(
        'route' => '/game/family-management/manage-family-top',
        'controller' => 'Ajax/family.manage.top.php'
    ),
    
    'manage-family-leave-costs'
    =>
    array(
        'route' => '/game/family-management/manage-family-leave-costs',
        'controller' => 'Ajax/family.manage.leave.costs.php'
    ),
    
    'mass-family-message'
    =>
    array(
        'route' => '/game/family-management/send-mass-message',
        'controller' => 'Ajax/family.mass.message.send.php'
    ),
    
    'request-family-alliance'
    =>
    array(
        'route' => '/game/family-management/request-family-alliance',
        'controller' => 'Ajax/family.alliance.request.php'
    ),
    
    'handle-family-alliance'
    =>
    array(
        'route' => '/game/family-management/handle-family-alliance',
        'controller' => 'Ajax/family.alliance.handle.php'
    ),
    
    'abolish-family'
    =>
    array(
        'route' => '/game/family-management/abolish-family',
        'controller' => 'Ajax/family.abolish.php'
    ),
    
    'buy-family-garage'
    =>
    array(
        'route' => '/game/buy-family-garage',
        'controller' => 'Ajax/family.garage.buy.php'
    ),
    
    'buy-family-crusher'
    =>
    array(
        'route' => '/game/family-garage/buy-family-crusher',
        'controller' => 'Ajax/family.crusher.converter.buy.php'
    ),
    
    'buy-family-converter'
    =>
    array(
        'route' => '/game/family-garage/buy-family-converter',
        'controller' => 'Ajax/family.crusher.converter.buy.php'
    ),
    
    'family-garage-interact-vehicles'
    =>
    array(
        'route' => '/game/family-garage/interact-vehicles',
        'controller' => 'Ajax/family.garage.interact.vehicles.php'
    ),
    
    'family-crimes-organize'
    =>
    array(
        'route' => '/game/family-crimes/organize',
        'controller' => 'Ajax/family.crimes.organize.php'
    ),
    
    'interact-family-crimes'
    =>
    array(
        'route' => '/game/family-crimes/interact',
        'controller' => 'Ajax/family.crimes.interact.php'
    ),
    
    'organize-family-raid'
    =>
    array(
        'route' => '/game/family-raid/organize',
        'controller' => 'Ajax/family.raid.organize.php'
    ),
    
    'interact-family-raid'
    =>
    array(
        'route' => '/game/family-raid/interact',
        'controller' => 'Ajax/family.raid.interact.php'
    ),
    
    'buy-bullets'
    =>
    array(
        'route' => '/game/bullet-factories/buy-bullets',
        'controller' => 'Ajax/bullet.factory.buy.bullets.php'
    ),
    
    'hitlist-order'
    =>
    array(
        'route' => '/game/hitlist/order',
        'controller' => 'Ajax/hitlist.order.php'
    ),
    
    'hitlist-buy-out'
    =>
    array(
        'route' => '/game/hitlist/buy-out',
        'controller' => 'Ajax/hitlist.buy.out.php'
    ),
    
    'murder-player'
    =>
    array(
        'route' => '/game/murder/player',
        'controller' => 'Ajax/murder.player.php'
    ),
    
    'set-backfire'
    =>
    array(
        'route' => '/game/murder/set-backfire',
        'controller' => 'Ajax/murder.set.backfire.php'
    ),
    
    'hire-detective'
    =>
    array(
        'route' => '/game/murder/hire-detective',
        'controller' => 'Ajax/murder.hire.detective.php'
    ),
    
    'weapon-training'
    =>
    array(
        'route' => '/game/murder/train-weapon-training',
        'controller' => 'Ajax/murder.train.weapon.php'
    ),
    
    'heal-member'
    =>
    array(
        'route' => '/game/hospital/heal-member',
        'controller' => 'Ajax/hospital.heal.php'
    ),
    
    'pimp-whores'
    =>
    array(
        'route' => '/game/red-light-district/pimp-hoes',
        'controller' => 'Ajax/rld.pimp.php'
    ),
    
    'pimp-for-player'
    =>
    array(
        'route' => '/game/pimp-for-player',
        'controller' => 'Ajax/rld.pimp.php'
    ),
    
    'buy-rld-windows'
    =>
    array(
        'route' => '/game/red-light-district/buy-rld-windows',
        'controller' => 'Ajax/rld.buy.windows.php'
    ),
    
    'remove-rld-windows'
    =>
    array(
        'route' => '/game/red-light-district/remove-rld-windows',
        'controller' => 'Ajax/rld.remove.windows.php'
    ),
    
    'poll-vote'
    =>
    array(
        'route' => '/game/poll/vote',
        'controller' => 'Ajax/poll.vote.php'
    ),
    
    /** SHOULD TRY WORK MORE LIKE THIS WHEN POSSIBLE AND IT IS ALREADY ALOT POSSIBLE IN EXISTING CODE..
    'forum-cat-topic-action'
    =>
    array(
        'route' => '/forum/[A-Za-z0-9-]{3,30}/[A-Za-z0-9-]{3,100}/#\b(delete|report|edit|move)\b#',
        'controller' => 'Ajax/forum.topic.php'
    ),
    
    'forum-cat-topic-reaction-action'
    =>
    array(
        'route' => '/forum/[A-Za-z0-9-]{3,30}/[A-Za-z0-9-]{3,100}/[1-9][0-9]{0,6}/#\b(delete|quote|report|edit|move)\b#',
        'controller' => 'Ajax/forum.reaction.php'
    ),
    **/
    
    'forum-new-topic'
    =>
    array(
        'route' => '/interact/forum/create-new-topic', // interact keyword present otherwise we end up in the forum page controller
        'controller' => 'Ajax/forum.create.new.topic.php'
    ),
    
    'forum-edit-topic'
    =>
    array(
        'route' => '/interact/forum/edit-topic', // interact keyword present otherwise we end up in the forum page controller
        'controller' => 'Ajax/forum.edit.topic.php'
    ),
    
    'forum-reply'
    =>
    array(
        'route' => '/interact/forum/reply-to-topic', // interact keyword present otherwise we end up in the forum page controller
        'controller' => 'Ajax/forum.reply.to.topic.php'
    ),
    
    'forum-edit-reaction'
    =>
    array(
        'route' => '/interact/forum/edit-reaction', // interact keyword present otherwise we end up in the forum page controller
        'controller' => 'Ajax/forum.edit.reaction.php'
    ),
    
    'new-market-item'
    =>
    array(
        'route' => '/game/market/new-market-item',
        'controller' => 'Ajax/market.add.item.php'
    ),
    
    'interact-market-item'
    =>
    array(
        'route' => '/game/market/interact-market-item',
        'controller' => 'Ajax/market.interact.php'
    ),
    
    'buy-garage'
    =>
    array(
        'route' => '/game/buy-garage',
        'controller' => 'Ajax/garage.buy.php'
    ),
    
    'sell-garage'
    =>
    array(
        'route' => '/game/sell-garage',
        'controller' => 'Ajax/garage.sell.php'
    ),
    
    'garage-interact-vehicle'
    =>
    array(
        'route' => '/game/garage/interact-vehicle',
        'controller' => 'Ajax/garage.interact.vehicle.php'
    ),
    
    'invite-block-user'
    =>
    array(
        'route' => '/game/friends-blocks/handle-block',
        'controller' => 'Ajax/friends.block.handle.php'
    ),
    
    'interact-friends-list'
    =>
    array(
        'route' => '/game/friends-blocks/interact-friendslist',
        'controller' => 'Ajax/friends.list.interact.php'
    ),
    
    'produce-drugs-liquids'
    =>
    array(
        'route' => '/game/drugs-liquids/produce',
        'controller' => 'Ajax/drugs.liquids.produce.php'
    ),
    
    'collect-drugs-liquids'
    =>
    array(
        'route' => '/game/drugs-liquids/collect',
        'controller' => 'Ajax/drugs.liquids.collect.php'
    ),
    
    'smuggle-units'
    =>
    array(
        'route' => '/game/smuggling/smuggle-unit',
        'controller' => 'Ajax/smuggle.interact.php'
    ),
    
    'interact-equipment'
    =>
    array(
        'route' => '/game/equipment-stores/interact',
        'controller' => 'Ajax/equipment.interact.php'
    ),
    
    'interact-residence'
    =>
    array(
        'route' => '/game/estate-agency/interact',
        'controller' => 'Ajax/residence.interact.php'
    ),
    
    'gym-training'
    =>
    array(
        'route' => '/game/gym/training',
        'controller' => 'Ajax/gym.training.php'
    ),
    
    'gym-fast-action'
    =>
    array(
        'route' => '/game/gym/fast-action-change',
        'controller' => 'Ajax/gym.fast.action.php'
    ),
    
    'gym-create-competition'
    =>
    array(
        'route' => '/game/gym/create-competition',
        'controller' => 'Ajax/gym.competition.create.php'
    ),
    
    'gym-competition-challenge'
    =>
    array(
        'route' => '/game/gym/competition/challenge',
        'controller' => 'Ajax/gym.competition.challenge.php'
    ),
    
    'ground-state-select'
    =>
    array(
        'route' => '/game/ground-map/location-select',
        'controller' => 'Ajax/ground.state.select.php'
    ),
    
    'buy-ground'
    =>
    array(
        'route' => '/game/ground-map/buy-area',
        'controller' => 'Ajax/ground.buy.php'
    ),
    
    'buy-ground-building'
    =>
    array(
        'route' => '/game/ground-map/buy-area-building',
        'controller' => 'Ajax/ground.buy.building.php'
    ),
    
    'upgrade-ground-building'
    =>
    array(
        'route' => '/game/ground-map/upgrade-area-building',
        'controller' => 'Ajax/ground.upgrade.building.php'
    ),
    
    'bomb-ground'
    =>
    array(
        'route' => '/game/ground-map/bomb-area',
        'controller' => 'Ajax/ground.bomb.php'
    ),
    
    'open-luckybox'
    =>
    array(
        'route' => '/game/luckybox/open-box',
        'controller' => 'Ajax/luckybox.open.php'
    ),
    
    'challenge-fifty-game'
    =>
    array(
        'route' => '/game/fifty-games/challenge',
        'controller' => 'Ajax/fifty.game.challenge.php'
    ),
    
    'create-fifty-game'
    =>
    array(
        'route' => '/game/fifty-games/create',
        'controller' => "Ajax/fifty.game.create.php"
    ),
    
    'play-dobbling'
    =>
    array(
        'route' => '/game/dobbling/play',
        'controller' => "Ajax/dobbling.play.php"
    ),
    
    'play-racetrack'
    =>
    array(
        'route' => '/game/racetrack/play',
        'controller' => "Ajax/racetrack.play.php"
    ),

    'play-streetrace'
    =>
    array(
        'route' => '/game/streetrace/play',
        'controller' => 'Ajax/streetrace.play.php'
    ),
    
    'play-roulette'
    =>
    array(
        'route' => '/game/roulette/play',
        'controller' => "Ajax/roulette.play.php"
    ),
    
    'play-slot-machine'
    =>
    array(
        'route' => '/game/slot-machine/play',
        'controller' => "Ajax/slot.machine.play.php"
    ),
    
    'play-blackjack'
    =>
    array(
        'route' => '/game/blackjack/play',
        'controller' => "Ajax/blackjack.play.php"
    ),
    
    'buy-lottery-ticket'
    =>
    array(
        'route' => '/game/lottery/buy-ticket',
        'controller' => 'Ajax/lottery.buy.ticket.php'
    ),
    
    'interact-donation-shop'
    =>
    array(
        'route' => '/game/donation-shop/interact',
        'controller' => 'Ajax/donation.shop.interact.php'
    ),
    
    'interact-family-property'
    =>
    array(
        'route' => '/game/family-properties/interact',
        'controller' => 'Ajax/family.property.interact.php'
    ),
    
    'buy-family-mercenaries'
    =>
    array(
        'route' => '/game/family-mercenaries/buy',
        'controller' => 'Ajax/family.mercenaries.buy.php'
    ),
    
    'remove-protection'
    =>
    array(
        'route' => '/game/status/remove-protection',
        'controller' => 'Ajax/status.remove.protection.php'
    ),
    
    'donate'
    =>
    array(
        'route' => '/game/donation-shop/donate',
        'controller' => 'Ajax/donate.php'
    ),
    
    'donator-list'
    =>
    array(
        'route' => '/game/information/team-members/apply-donator-list',
        'controller' => 'Ajax/donator.list.php'
    ),
    
    /**
     * Admin Ajax routes
     * **/
    
    'admin-sort'
    =>
    array(
        'route' => '/admin/sort',
        'controller' => 'admin/Ajax/sort.php'
    ),
    
    'admin-delete'
    =>
    array(
        'route' => '/admin/delete',
        'controller' => 'admin/Ajax/delete.php'
    ),
    
    'admin-delete-confirm'
    =>
    array(
        'route' => '/admin/delete-confirm',
        'controller' => 'admin/Ajax/delete-confirm.php'
    ),
    
    'admin-activate'
    =>
    array(
        'route' => '/admin/activate',
        'controller' => 'admin/Ajax/activate.php'
    ),
    
    'admin-deactivate'
    =>
    array(
        'route' => '/admin/deactivate',
        'controller' => 'admin/Ajax/deactivate.php'
    ),
    
    'admin-edit'
    =>
    array(
        'route' => '/admin/edit/[0-9][0-9]{0,6}',
        'controller' => 'admin/Ajax/edit.php'
    ),
    
    'admin-edit-save'
    =>
    array(
        'route' => '/admin/edit-save',
        'controller' => 'admin/Ajax/edit.save.php'
    ),
    
    'admin-new'
    =>
    array(
        'route' => '/admin/new',
        'controller' => 'admin/Ajax/new.php'
    ),
    
    'admin-search'
    =>
    array(
        'route' => '/admin/search',
        'controller' => 'admin/Ajax/search.php'
    )
);
