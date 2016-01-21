/**
* Lightbox
*
* This libary is used to create a lightbox in a web application.  This library
* requires the Prototype 1.6 library and Script.aculo.us core, effects, and dragdrop
* libraries.  To use, add a div containing the content to be displayed anywhere on 
* the page.  To create the lightbox, add the following code:
*
*	var test;
*	
*	Event.observe(window, 'load', function () {
*		test = new Lightbox('idOfMyDiv');
*	});
*	
*	Event.observe('lightboxLink', 'click', function () {
*		test.open();
*	});
*
*	Event.observe('closeLink', 'click', function () {
*		test.close();
*	});
*     
*/
(function() {
    var b = document.body || document.documentElement;
    var s = b.style;
    var p = 'transition';
    if(typeof s[p] == 'string') { window.trans = true; return; }

    // Tests for vendor specific prop
    v = ['Moz', 'Webkit', 'Khtml', 'O', 'ms'],
    p = p.charAt(0).toUpperCase() + p.substr(1);
    for(var i=0; i<v.length; i++) {
      if(typeof s[v[i] + p] == 'string') { window.trans = true; return;}
    }
    window.trans = false;
})();
window.globalLightboxZIndexCounter=1500;
window.globalOpenedLightboxCounter=0;
var Lightbox = Class.create({
	open : function () {
        if (!this.noClose) {
            window.document.observe('keydown',this._closeOnEscapeHandler);
        }
        this._centerWindow(this.container);
        this.container.fire('lb:beforeopen',this);
        this.container.style.zIndex = 1 + (++window.globalLightboxZIndexCounter);
        this._fade('open', this.container);
        window.globalOpenedLightboxCounter++;
        this.container.fire('lb:afteropen',this);
        window.document.fire('somelb:opened',this);
        if (!this.noClose) {
            $('bg_fade').observe('click', function(event){
                this.close();
            }.bind(this));
        }
	},
	
    observe : function(event,handler){
        this.container.observe(event,handler);
    },
    
    stopObserving : function(event, handler){
    	this.container.stopObserving(event, handler);
    },

	close : function (dontFade) {
        window.globalOpenedLightboxCounter--;
        window.document.stopObserving('keydown',this._closeOnEscapeHandler);
        this.container.fire('lb:beforeclose',this);
		this._fade('close', this.container, dontFade);
		this.container.fire('lb:afterclose',this);
        $('bg_fade').stopObserving('click');
	},
	
	_fade : function fadeBg(userAction,whichDiv, dontFade){
		if(userAction=='close'){
            if(window.globalOpenedLightboxCounter==0){
                if (typeof dontFade == 'undefined')
                    if (!window.trans) {
                        new Effect.Fade(this.bgFade,
                                           {duration:.5,
                                            from:0.4,
                                            to:0,
                                            afterFinish:this._makeInvisible.bind(this),
                                            afterUpdate:this._hideLayer(whichDiv)});
                    } else {
                        this.bgFade.removeClassName('visible');
                        this._hideLayer(whichDiv);
                        setTimeout(function() {
                            this._makeInvisible();
                        }.bind(this), 400);
                    }
                else
                    this._hideLayer(whichDiv);
                    
            }else{
                this._hideLayer(whichDiv);
            }
        }else{

            if(this.bgFade.style.visibility=="visible")
                this._showLayer(whichDiv);
            else
                if (!window.trans) {
                    new Effect.Appear(this.bgFade,
                               {duration:.5,
                                from:0,
                                to:0.4,
                                beforeUpdate:this._makeVisible.bind(this),
                                afterFinish:this._showLayer(whichDiv)});
                } else {
                    this._makeVisible();
                    this.bgFade.show();
                    this._showLayer(whichDiv);
                    this.bgFade.addClassName('visible');
                }
		}
	},

	_makeVisible : function makeVisible(){
        this.bgFade.style.visibility="visible";
        this.bgFade.style.zIndex = "299";
	},

	_makeInvisible : function makeInvisible(){
		this.bgFade.style.visibility="hidden";
	},

	_showLayer : function showLayer(userAction){
		$(userAction).style.display="block";
	},
	
	_hideLayer : function hideLayer(userAction){
		$(userAction).style.display="none";
	},
	
	_centerWindow : function centerWindow(element) {
		var windowHeight = parseFloat($(element).getHeight())/2; 
		var windowWidth = parseFloat($(element).getWidth())/2;
        var scrollTop= self.pageYOffset || (document.documentElement && document.documentElement.scrollTop) || (document.body && document.body.scrollTop);

		if(typeof window.innerHeight != 'undefined') {
			$(element).style.top = Math.round(document.body.offsetTop + ((window.innerHeight - $(element).getHeight()))/2)+'px';
			$(element).style.left = Math.round(document.body.offsetLeft + ((window.innerWidth - $(element).getWidth()))/2)+'px';
		} else {
			$(element).style.top = Math.round(document.body.offsetTop + ((document.documentElement.offsetHeight - $(element).getHeight()))/2)+'px';
			$(element).style.left = Math.round(document.body.offsetLeft + ((document.documentElement.offsetWidth - $(element).getWidth()))/2)+'px';
		}
		var h = (window.innerHeight ? window.innerHeight : (document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.offsetHeight));
         $(element).style.top = Math.round(scrollTop  + h/2- windowHeight)+'px';
        console.log('scrollTop '+scrollTop)
        console.log('windowHeight '+windowHeight)
        console.log('h '+h)

        if (parseInt($(element).style.top) < 50)
            $(element).style.top = '50px';

	},
	
    _closeOnEscape : function(event){
        if(event.keyCode == 27)
        {
            this.close()
        }
    },
	initialize : function(containerDiv) {
		this.container = containerDiv;
        this.container.style.zIndex = 1 + (++window.globalLightboxZIndexCounter);        
        this._hideLayer(this.container);
        this._closeOnEscapeHandler = this._closeOnEscape.bind(this);
		var bgFade = $('bg_fade');
		if(!bgFade) {
			bgFade = $(document.createElement('div'));
			bgFade.id = 'bg_fade';
			bgFade.addClassName('bg-fade');
            if (Prototype.Browser.IE) {
                bgFade.addClassName('class','bg-fade');
            }
            bgFade.style.display = 'none';
			document.body.insertBefore(bgFade, document.body.firstChild);
		}
		this.bgFade = bgFade;
        $(document.body).insert({top: this.container});

        if (this.container.select('.lb-close')) {
            this.container.select('.lb-close').each(function(th) {
                th.observe('click', function() {
                    this.close();
                }.bind(this));
            }.bind(this));
        }

	}
});

var TemplateLightbox = Class.create(Lightbox, {
    initialize: function($super, template, evalParams) {
        var inner = new Template(template.innerHTML).evaluate(evalParams);;
        var containerDiv = new Element('div', {'class': 'lightbox'}).update(inner);
        $(document.body).insert({'top': containerDiv});
        $super(containerDiv);
        this.container = containerDiv;
        if (Prototype.Browser.IE) {
            containerDiv.addClassName('lightbox');
        }
        this.content = containerDiv.down('.lb-content');

        if (evalParams.cssClass) { containerDiv.addClassName(evalParams.cssClass); }
    }
});
var InlineLightbox = Class.create(TemplateLightbox, {
    initialize: function($super, template, params) {
        this.loaded = false;
        this.params = params;
        $super(template, params);
    },

    open: function($super) {
        var self = this;
//        var parameters =  (this.params.parameters)? this.params.parameters : {};
        var _element_id=this.params.id;
        self.content.update($(_element_id));
        $(_element_id).show();
        self._centerWindow(self.container);

        $super();
    }


});
var AjaxLightbox = Class.create(TemplateLightbox, {
    initialize: function($super, template, params) {
        this.loaded = false;
        this.params = params;
        $super(template, params);
    },

    open: function($super) {
        if (!this.loaded) {
            this.load();
        }
        $super();
    },

    load: function() {
        var self = this;
        var parameters =  (this.params.parameters)? this.params.parameters : {};
        this.container.addClassName('loading');
        if (this.params.href) {
            new Ajax.Request( this.params.href, {
                method: 'get',
                parameters: parameters,
                onComplete: function(transport) {
                    self.content.update(transport.responseText);
                    self.container.removeClassName('loading');
                    if (self.params.callback) {
                        self.params.callback(self);
                    }
                    self.loaded = true;
                    self._centerWindow(self.container);
                }
            });
        }
    }
});
