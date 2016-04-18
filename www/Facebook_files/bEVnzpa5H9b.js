/*!CK:184564053!*//*1460467007,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["cEK9F"]); }

__d('AccessibilityWebVirtualCursorClickLogger',['AccessibilityConfig','AccessibilityWebAssistiveTechTypedLogger','VirtualCursorStatus'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();var h={init:function(){for(var i=arguments.length,j=Array(i),k=0;k<i;k++)j[k]=arguments[k];j.forEach(function(l){c('VirtualCursorStatus').add(l,this._log);}.bind(this),this);},_log:function(i,j){if(c('AccessibilityConfig').a11yVirtualCursorTrigger)if(i)new (c('AccessibilityWebAssistiveTechTypedLogger'))().setIndicatedBrowsers(j).log();}};f.exports=h;},null);