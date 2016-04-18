/*!CK:2116591129!*//*1460391168,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["nzOWF"]); }

__d('UFIReactionsTheme',['ArbiterMixin','AsyncRequest','UFIConfig','XUFIReactionsSelectThemeController'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();var h=c('UFIConfig').reactionsTheme,i=babelHelpers['extends']({},c('ArbiterMixin'),{getThemeClassName:function(){return h?h.className:null;},getThemeID:function(){return h?h.id:null;},setThemeID:function(j){new (c('AsyncRequest'))().setURI(c('XUFIReactionsSelectThemeController').getURIBuilder().getURI()).setData({theme:j}).setHandler(function(k){h=k.getPayload();i.inform('change',h);}).send();}});f.exports=i;},null);