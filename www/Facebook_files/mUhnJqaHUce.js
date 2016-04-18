/*!CK:2133686933!*//*1460818809,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["sqgxm"]); }

__d("NotificationConstants",[],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();f.exports={PayloadSourceType:{UNKNOWN:0,USER_ACTION:1,LIVE_SEND:2,ENDPOINT:3,INITIAL_LOAD:4,OTHER_APPLICATION:5,SYNC:6}};},null);
__d('NotificationTokens',['CurrentUser'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();var h={tokenizeIDs:function(i){return i.map(function(j){return c('CurrentUser').getID()+':'+j;});},untokenizeIDs:function(i){return i.map(function(j){return j.split(':')[1];});}};f.exports=h;},null);
__d('NotificationUpdates',['Arbiter','BizSiteIdentifier.brands','ChannelConstants','JSLogger','NotificationConstants','NotificationTokens','LiveTimer','createObjectFrom'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();var h={},i={},j={},k={},l=[],m=0,n=c('JSLogger').create('notification_updates');function o(){if(m)return;var t=h,u=i,v=j,w=k;h={};i={};j={};k={};q('notifications-updated',t);if(Object.keys(u).length)q('seen-state-updated',u);if(Object.keys(v).length)q('read-state-updated',v);if(Object.keys(w).length)q('hidden-state-updated',w);l.pop();}function p(){if(l.length)return l[l.length-1];return c('NotificationConstants').PayloadSourceType.UNKNOWN;}function q(event,t){s.inform(event,{updates:t,source:p()});}var r=null,s=Object.assign(new (c('Arbiter'))(),{getserverTime:function(){return r;},handleUpdate:function(t,u){if(u.nodes&&Array.isArray(u.nodes))u.nodes=this._filterNodesBasedOnBusinessID(u.nodes);if(u.servertime){r=u.servertime;c('LiveTimer').restart(u.servertime);}if(Object.keys(u).length)this.synchronizeInforms(function(){l.push(t);var v=babelHelpers['extends']({payloadsource:p()},u);this.inform('update-notifications',v);this.inform('update-seen',v);this.inform('update-read',v);this.inform('update-hidden',v);}.bind(this));},didUpdateNotifications:function(t){Object.assign(h,c('createObjectFrom')(t));o();},didUpdateSeenState:function(t){Object.assign(i,c('createObjectFrom')(t));o();},didUpdateReadState:function(t){Object.assign(j,c('createObjectFrom')(t));o();},didUpdateHiddenState:function(t){Object.assign(k,c('createObjectFrom')(t));o();},synchronizeInforms:function(t){m++;try{t();}catch(u){throw u;}finally{m--;o();}},_filterNodesBasedOnBusinessID:function(t){return t.filter(function(u){return u.business_ids&&Object.keys(u.business_ids).length>0?!!('business_ids_user_pref' in u?u.business_ids_user_pref[c('BizSiteIdentifier.brands').getBusinessID()]:u.business_ids[c('BizSiteIdentifier.brands').getBusinessID()]):!c('BizSiteIdentifier.brands').isBizSite();});}});c('Arbiter').subscribe(c('ChannelConstants').getArbiterType('notification_json'),function(t,u){var v=Date.now(),w=u.obj.nodes;if(w){w.forEach(function(x){x.receivedTime=v;});n.debug('notifications_received',w);s.handleUpdate(c('NotificationConstants').PayloadSourceType.LIVE_SEND,u.obj);}});c('Arbiter').subscribe(c('ChannelConstants').getArbiterType('notifications_seen'),function(t,u){var v=c('NotificationTokens').tokenizeIDs(u.obj.alert_ids);s.handleUpdate(c('NotificationConstants').PayloadSourceType.LIVE_SEND,{seenState:c('createObjectFrom')(v)});});c('Arbiter').subscribe(c('ChannelConstants').getArbiterType('notifications_read'),function(t,u){var v=c('NotificationTokens').tokenizeIDs(u.obj.alert_ids);s.handleUpdate(c('NotificationConstants').PayloadSourceType.LIVE_SEND,{readState:c('createObjectFrom')(v)});});c('Arbiter').subscribe(c('ChannelConstants').getArbiterType('notification_hidden'),function(t,u){var v=c('NotificationTokens').tokenizeIDs([u.obj.notif_id+'']);s.handleUpdate(c('NotificationConstants').PayloadSourceType.LIVE_SEND,{HiddenState:c('createObjectFrom')(v)});});f.exports=s;},null);
__d('NotificationStore',['BizSiteIdentifier.brands','KeyedCallbackManager','NotificationConstants','NotificationUpdates','RangedCallbackManager','MercuryServerDispatcher'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();var h=function(m){this.notifications=new (c('KeyedCallbackManager'))();var n=function(o){var p=this.notifications.getResource(o);return p.creation_time;};this.order=new (c('RangedCallbackManager'))(n.bind(this),function(o,p){return p-o;});this.graphQLPageInfo={};},i=c('BizSiteIdentifier.brands').isBizSite()?c('BizSiteIdentifier.brands').getBusinessID():null;c('NotificationUpdates').subscribe('update-notifications',function(m,n){var o=n.endpoint||k;if(n.page_info)j[o].graphQLPageInfo=n.page_info;if(n.nodes===undefined)return;var p,q=[],r={},s=n.nodes||[],t;s.forEach(function(u){p=u.alert_id;t=j[o].notifications.getResource(p);if(!t||t.creation_time<u.creation_time){q.push(p);r[p]=u;}});j[o].notifications.addResourcesAndExecute(r);j[o].order.addResources(q);c('NotificationUpdates').didUpdateNotifications(q);});var j={},k='/ajax/notifications/client/get.php',l={getNotifications:function(m,n){var o=arguments.length<=2||arguments[2]===undefined?k:arguments[2],p=j[o].order.executeOrEnqueue(0,m,function(w){var x=j[o].notifications.executeOrEnqueue(w,n);}),q=j[o].order.getUnavailableResources(p);if(q.length){j[o].order.unsubscribe(p);if(!l.canFetchMore(o)){j[o].notifications.executeOrEnqueue(j[o].order.getAllResources(),n);return;}var r=j[o].graphQLPageInfo,s=r&&r.end_cursor||null,t;if(s){var u=Math.max.apply(null,q),v=j[o].order.getCurrentArraySize();t=u-v+1;}else t=m;c('MercuryServerDispatcher').trySend(o,{businessID:i,cursor:s,length:t});}},getAll:function(m){var n=arguments.length<=1||arguments[1]===undefined?k:arguments[1];l.getNotifications(l.getCount(n),m,n);},getCount:function(){var m=arguments.length<=0||arguments[0]===undefined?k:arguments[0];return j[m].order.getAllResources().length;},canFetchMore:function(){var m=arguments.length<=0||arguments[0]===undefined?k:arguments[0],n=j[m].graphQLPageInfo;return (!n||!n.hasOwnProperty('has_next_page')||n.has_next_page);},registerEndpoint:function(m){if(m&&!(m in j)){j[m]=new h(m);var n={};n[m]={mode:c('MercuryServerDispatcher').IMMEDIATE,handler:function(o){o.endpoint=m;c('NotificationUpdates').handleUpdate(c('NotificationConstants').PayloadSourceType.ENDPOINT,o);}};c('MercuryServerDispatcher').registerEndpoints(n);}},setBusinessID:function(m){i=m;}};l.registerEndpoint(k);f.exports=l;},null);
__d("XNotificationsOptionsController",["XController"],function a(b,c,d,e,f,g){c.__markCompiled&&c.__markCompiled();f.exports=c("XController").create("\/notifications\/feedback\/option\/",{notif_id:{type:"Int",required:true},undo:{type:"Bool",defaultValue:false},server_action:{type:"String",required:true}});},null);
__d('NotificationUserActions',['AsyncRequest','NotificationConstants','NotificationStore','NotificationTokens','NotificationUpdates','URI','XNotificationsOptionsController','createObjectFrom','emptyFunction'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();var h=c('NotificationConstants').PayloadSourceType.USER_ACTION,i='mark_spam',j='turn_off',k='undo',l='original_subscription_level',m=false;function n(t){new (c('AsyncRequest'))('/ajax/notifications/mark_read.php').setData(t).send();}function o(t){var u={};t.forEach(function(v,w){u['alert_ids['+w+']']=v;});return u;}function p(t,u,v,w,x){var y=c('NotificationTokens').untokenizeIDs([t])[0],z={notification_id:y,client_rendered:true,request_type:u};Object.assign(z,v);new (c('AsyncRequest'))('/ajax/notifications/negative_req.php').setData(z).setHandler(w||c('emptyFunction')).setErrorHandler(x||c('emptyFunction')).send();}function q(t,u,v,w,x){var y=c('XNotificationsOptionsController').getURIBuilder().setInt('notif_id',c('NotificationTokens').untokenizeIDs([t])[0]).setBool('undo',w).setString('server_action',x).getURI(),z=function(aa){if(!aa.payload)throw new Error('No response from notif option!');c('NotificationUpdates').handleUpdate(h,{hiddenState:c('createObjectFrom')([t],!aa.payload.visible)});u(aa.payload);};new (c('AsyncRequest'))(y).setHandler(z||c('emptyFunction')).setErrorHandler(v||c('emptyFunction')).send();}function r(t,u,v,w,x){var y=x?k:j;c('NotificationStore').getAll(function(z){var aa=Object.keys(z).filter(function(ba){var ca=z[ba];return !!(ca.application&&ca.application.id&&ca.application.id==u);});p(t,y,null,function(ba){v(ba);c('NotificationUpdates').handleUpdate(h,{hiddenState:c('createObjectFrom')(aa,!x)});},w);});}var s={markNotificationsAsSeen:function(t){c('NotificationUpdates').handleUpdate(h,{seenState:c('createObjectFrom')(t)});var u=c('NotificationTokens').untokenizeIDs(t),v=o(u);v.seen=true;n(v);},setNextIsFromReadButton:function(t){m=t;},markNotificationsAsRead:function(t){c('NotificationUpdates').handleUpdate(h,{readState:c('createObjectFrom')(t)});var u=c('NotificationTokens').untokenizeIDs(t),v=o(u);if(m){v.from_read_button=true;m=false;}n(v);},sendNotifOption:function(t,u,v,w){q(t,u,v,false,w);},undoNotifOption:function(t,u,v,w){q(t,u,v,true,w);},markNotificationAsHidden:function(t,u,v){c('NotificationUpdates').handleUpdate(h,{hiddenState:c('createObjectFrom')([t])});p(t,j,null,u,v);},markNotificationAsVisible:function(t,u,v,w){c('NotificationUpdates').handleUpdate(h,{hiddenState:c('createObjectFrom')([t],false)});var x=null;if(u!==null){x={};x[l]=u;}p(t,k,x,v,w);},markNotificationAsSpam:function(t,u,v){c('NotificationUpdates').handleUpdate(h,{hiddenState:c('createObjectFrom')([t],true)});p(t,i,null,u,v);},markAppAsHidden:function(t,u,v,w){var x=false;r(t,u,v,w,x);},markAppAsVisible:function(t,u,v,w){var x=true;r(t,u,v,w,x);}};f.exports=s;},null);
__d('legacy:CompactTypeaheadRenderer',['CompactTypeaheadRenderer'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();if(!b.TypeaheadRenderers)b.TypeaheadRenderers={};b.TypeaheadRenderers.compact=c('CompactTypeaheadRenderer');},3);