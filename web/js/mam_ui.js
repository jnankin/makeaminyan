
var MAMUI = {

        block : function(contents){
            if (!contents) contents = "<div class='warningModal' style='width: 200px;'> <h2>Loading... <img src=\"/images/ajax-loader.gif\" /> </h2></div>";
            MAMUI.blockModal = new Boxy(contents, {closeable: false, modal : true})
        },

        unblock : function(){
            if (MAMUI.blockModal) MAMUI.blockModal.hideAndUnload();

            delete MAMUI.blockModal;
        },

	notify : function(text, level, speed){
		if (!speed) speed = 3000;
		$.notifyBar({
			html: text,
			className: level,
			delay: speed
		});
	},

        helpSnippetTooltip : function(e){
            var name = $(this).attr('rel');
            if ($('#helpSnippet-' + name).length > 0) return;
            if (e.pageX){
                var top = e.pageY;
                var left = e.pageX;
            }
            else {
                var top = e.clientY;
                var left = e.clientX;
            }

            if (!K.Utils.isEmpty(name)){
                $.get('/helpSnippet/getSnippet', {'name' : name}, function(data){
                    var tag = $('<div class="helpSnippetTooltip" />').html(data);
                    tag.css('top', top);
                    tag.css('left', left);
                    tag.attr('id', 'helpSnippet-' + name);
                    tag.find('a.snippetClose').click(function(){tag.remove();})
                    $('body').append(tag);
                }, 'html');
            }
        },

        unpersistHelpSnippet : function(name){
            $.get('/helpSnippet/unpersistSnippet', {'name' : name}, function(data){
                if (data.success){
                    $('#helpSnippet-' + name).hide();
                    $('#' + name + '-launcher').show();
                    $('#helpSnippet-' + name + ' .stopShowing').remove();
                }
            }, 'json');
        },

	notifyError : function(text, speed){this.notify(text || "There was an error in processing your request.", 'error', speed);},
	notifySuccess : function(text, speed){this.notify(text, 'success', speed);},

	successMessage: function(interiorHTML){
		return $('<div class="successMessage"><div>').html(interiorHTML);
	},

	errorMessage: function(interiorHTML){
		return $('<div class="errorMessage"><div>').html(interiorHTML);
	},

	modal: function (body, title, width, height, options){
		if (!options) options = {};
		if (title != undefined) options.title = title;
		if (options.modal === undefined) options.modal = true;
		options.closable = true;

                //we might allow non-modal dialogs later
                if (options.modal && MAMUI.Modals.activeModal && MAMUI.Modals.activeModal.visible) return;
		var el = new Boxy($(body).clone(), options);
		if (options.modal) MAMUI.Modals.activeModal = el;

		if (width != undefined || height != undefined) el.resize(width, height).center();
                return el;
	},

        showModal : function(name, options){
            return MAMUI.Modals.show(name, options);
        },

        closeModal : function(name, unload){
            return MAMUI.Modals.close(name, unload);
        },


        //assumes that in the selector for the div given, there's a top level element with class 'title' and a top level element with class 'wrapper'
        //h2 also has a 'collapse' event added to it
        makeCollapsibleTitle : function(selector, options){
            if (!options) options = {};

            for (var i = 0; i < $(selector).length; i++){
                var currentDiv = $($(selector).get(i));
                var title = currentDiv.find('.title');
                var a = $("<a class='expanded' href='javascript:void(0)'>").text(title.text());

                if (options.collapseOnclick) {
                    a.addClass('collapseOnClick');
                }
                title.html('');
                title.append(a);

                currentDiv.find('.wrapper').show();

                a.click(function(){
                   var collapse = !$(this).hasClass('collapsed');

                   if (collapse && !$(this).hasClass('collapseOnClick')) return;

                   if (collapse){
                        $(this).trigger('collapse');
                   }
                   else {
                        $(this).trigger('expand');
                   }

                   return false;
                });

                a.bind('collapse', function(){
                        $(this).removeClass('expanded');
                        $(this).addClass('collapsed');
                        $(this).parent('.title').siblings('.wrapper').hide();
                });

                a.bind('expand', function(){
                        $(this).removeClass('collapsed');
                        $(this).addClass('expanded');
                        $(this).parent('.title').siblings('.wrapper').show();
                });

                if (options.collapsed){
                    a.trigger('collapse');
                }


            }
        },

        ImageSelector : {
            create : function(options) {
                var ajaxOptions = {};
                ajaxOptions['ajax'] = '/file/imageSelector';
                ajaxOptions['params'] = {};

                if (options['title'])
                    ajaxOptions['title'] = options['title'];
                else
                    ajaxOptions['title'] = 'Select an image';

                ajaxOptions['modal'] = true;
                ajaxOptions['afterShow'] = function(){
                    MAMUI.ImageSelector.init()
                };

                if (K.MSB && K.MSB.siteId) ajaxOptions['params'].siteId = K.MSB.siteId;
                if (options['onSelect']) this.onSelect = options['onSelect'];
                MAMUI.showModal('imageSelector', ajaxOptions);
            },

            selectSiteImage : function(){
                var ids = $(this).attr('id').replace('imageSelectorSite_', '').split('_');
                MAMUI.ImageSelector.selectImage($(this).attr('rel'), {'url' : $(this).attr('rel'), 'id' : ids[0], 'filestoreId' : ids[1]});
            },

            selectImage : function(url, data){
                MAMUI.closeModal('imageSelector', true);
                if (MAMUI.ImageSelector.onSelect)
                    MAMUI.ImageSelector.onSelect(url, data);
            }

        },

        Tabs : {
            showTab : function(event, context){
                if (!context) context = this;

                //turn off all the other tabs
                var tabContainer = $(context).parents('ul');
                var tabClass = tabContainer.attr('rel');
                $(tabContainer).find('a').removeClass('selected');

                //turn on this tab
                $(context).addClass('selected');

                if (!tabClass) {
                    var listElement = $(context).parent('li');
                    var list = $(listElement).parent('ul');
                    var itemIndex = list.find('li').index(listElement);
                    if (itemIndex >= 0){
                        list.parent().children('div').hide();
                        $(list.parent().children('div').get(itemIndex)).show();
                    }
                }
                else {
                    //show the content
                    $('div.' + tabClass).hide();
                    $('div#' + $(context).attr('rel')).show();
                }
            },

            init : function(selector){
                if (!selector) selector = 'ul.tabs';
                $(selector).each(function(idx, val){
                    if (!$(val).attr('rel')){
                        var children = $(val).parent().children('div');
                        children.hide();
                        children.first().show();

                        var selectedA = $(val).find('li > a').first();
                        if ($(val).find('a.selected').length > 0) selectedA = $(val).find('a.selected').get(0);
                        MAMUI.Tabs.showTab(null, selectedA);

                    }
                    else {
                        $('div.' + $(val).attr('rel')).hide();
                        $('div#' + $(val).find('a.selected').attr('rel')).show();
                    }
                });
            }
        },

        Modals : {
            registry : {},

            register : function (name, title, width, height, options){
                MAMUI.Modals.registry[name] = {
                    "title": title,
                    "width": width,
                    "height": height,
                    "options":options
                };

                return MAMUI.Modals.registry[name];
            },

            closeSelf : function(element){
                var boxy = $(element).parents('table.boxy-wrapper').data('boxy');
                if (boxy) boxy.hideAndUnload();
            },

            show : function(name, options){
                if (!options) options = {};


                if (!MAMUI.Modals.registry[name]){
                    //load modal from url
                    if (options.ajax){
                        if (options.modal && MAMUI.Modals.activeModal && MAMUI.Modals.activeModal.visible) return;
                        options.closeFunc = function(){MAMUI.Modals.close(name, true);} ;
                        $.get(options.ajax, options.params, function(data){
                            MAMUI.Modals.registry[name] = {
                                'options' : options,
                                'instance' : new Boxy(data, options)
                            };

                            if (options.modal) MAMUI.Modals.activeModal = MAMUI.Modals.registry[name].instance;
                        },
                        "string");
                    }
                    return false;
                }

                var entry = MAMUI.Modals.registry[name];
                var instance = MAMUI.modal('#' + name,
                    options.title || entry.title,
                    options.width || entry.width,
                    options.height || entry.height,
                    Utils.combineObjects(options, entry.options)
                );
                entry.instance = instance;
                return instance;
            },

            close : function(name, unload){
                if (!MAMUI.Modals.registry[name]) return false;
                var entry = MAMUI.Modals.registry[name];

                if (entry && entry.instance){
                    if (unload){
                       entry.instance.hideAndUnload();
                       if (entry.options.ajax){
                           delete MAMUI.Modals.registry[name];
                       }
                    }
                    else {
                        entry.instance.hide();
                    }
                }
            }

        }
};

$().ready(function(){
   $('ul.tabs a').live('click', MAMUI.Tabs.showTab);
   $('img.helpSnippetLauncher').live('click', MAMUI.helpSnippetTooltip);
   MAMUI.Tabs.init();
});