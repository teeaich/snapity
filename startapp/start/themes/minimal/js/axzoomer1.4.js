/*!
 * jQuery axzoomer
 * Alban Xhaferllari
 * albanx@gmail.com
 * Copyright 2011, AUTHORS.txt (http://www.albanx.com)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * Please preserve this heading always
 * http://jquery.org/license
 *
 * 
 */

(function($)
{
	var methods =
	{
		init : function(options)
		{
    	    return this.each(function() 
    	    {
    		    var settings = 
    		    {
    		    	'maxZoom':4,
    		    	'zoomIn':'zoom-in.png',
    		    	'zoomOut':'zoom-out.png',
    		    	'reset':false,
    		    	'showControls':true,
    		    	'controlsPos':['right','bottom'],
    		    	'opacity':0.5,
    		    	'sensivity':10,
    		    	'overrideMousewheel':true,
    		    	'mousewheel':true
    		    };
				if(this.tagName!='IMG')//apply only to tag image
				{
					$.error('Zoomer plugin applies only to img tag.');
					return false;
				}
				var $this=$(this);
				
				//prevent applying zoom plugin twice
				if($this.hasClass('ax-zoom'))	return;
				
				//extends default options
				if(options) $.extend(settings,options);
				
				//get image initial width and height if set in css code, otherwise real width will be loaded
				var MAIN_WIDTH=this.offsetWidth;
				var MAIN_HEIGHT=this.offsetHeight;
				
				//mem old settings for destroy call
				$this.data('init-status',{
					position:$this.css('position'),
					parent:$this.parent(),
					left:$this.css('left'),
					top:$this.css('top'),
					src:$this.attr('src')
				})
				 .addClass('ax-zoom')
				 .css({'position':'absolute','top':0,'left':0})
				 .data('settings',settings);

				// Detect device type, normal mouse or touchy(ipad android)
	            var touchy=("ontouchstart" in document.documentElement)?true:false;
				var move='touchmove.axzoomer mousemove.axzoomer';
				var end='touchend.axzoomer mouseup.axzoomer';
				var down='touchstart.axzoomer mousedown.axzoomer';

				//create the container div and append to image parent
				var container=$('<div class="ax-container" style="position:relative;overflow:hidden;left:0;top:0;" />')
							  .appendTo($this.parent())
							  .hover(function(){
								  if(settings.showControls)	controlsDiv.show('fade');
							  },function(){
								  if(settings.showControls)	controlsDiv.hide('fade');
							  });
				$this.data('container',container);//mem for desotroy method
				
				//div window for zoom view
				var windowDiv=$('<div class="ax-window" style="position:absolute;overflow:hidden;left:0;top:0;" />')
							  .appendTo(container)//attach to first parent
							  .append($this);//the move img to div

				//Div containing buttons and controls
				var controlsDiv=$('<div class="ax-controls"/>')
								.appendTo(container).hide()
								.css({'position':'absolute','opacity':settings.opacity,'height':32});
				
				//function to zoom on button down using timers
				var zoomtimer;
				
				$this.controlZoom=function(x)
				{
					$this.zoomInOut(x);
					zoomtimer=setTimeout(function(){
						$this.controlZoom(x);
					},30); 
				};
				
				//zoom in button	
				var customIn=($this.attr('zoomIn'))?$this.attr('zoomIn'):"";
				var zoombut_in=(customIn.indexOf("#")!=-1)?
								$(customIn):
								$('<img src="'+settings.zoomIn+'" alt="+" />').appendTo(controlsDiv).css('cursor','pointer').load(function(){setUpControls(controlsDiv);});
				zoombut_in.bind(down,function(e)
						  {
							  e.stopPropagation();
							  MOUSEX=MAIN_WIDTH/2;
							  MOUSEY=MAIN_HEIGHT/2;
							  $this.controlZoom(0.05);
						  })
						  .bind(end+' mouseout',function(){  clearTimeout(zoomtimer);});			
				
				//zoom out button
				var customOut=($this.attr('zoomOut')!=undefined)?$this.attr('zoomOut'):"";
				var zoombut_out=(customOut.indexOf("#")!=-1)?
									$(customOut):
									$('<img src="'+settings.zoomOut+'" alt="-" />').appendTo(controlsDiv)
																				   .css('cursor','pointer')
																				   .load(function(){setUpControls(controlsDiv);});
				
				zoombut_out.bind(down,function(e)
							{
							   e.stopPropagation();
							   MOUSEX=MAIN_WIDTH/2;
							   MOUSEY=MAIN_HEIGHT/2;
							   $this.controlZoom(-0.05);
							})
							.bind(end+' mouseout',function(){   clearTimeout(zoomtimer);});

				//reset button
				if(settings.reset)
				{
					var customRes=($this.attr('reset')!=undefined)?$this.attr('reset'):"";
					var reset=(customRes.indexOf("#")!=-1)?
									$(customRes):
									$('<img src="'+settings.reset+'" alt="O" />').appendTo(controlsDiv)
																				 .css('cursor','pointer')
																				 .load(function(){setUpControls(controlsDiv);});
	
					reset.bind(down,function(e)
						{
						   e.stopPropagation();
						   MOUSEX=MAIN_WIDTH/2;
						   MOUSEY=MAIN_HEIGHT/2;
						   $this.zoomInOut(-$this.zoomLevel);
						});
				}
				//sources
				var MAIN_SRC=$this.attr('src');//normal size source
				var ZOOM_SRC=$this.attr('src-big');//hight resolution source of image

				var MAIN_TOP=0,MAIN_LEFT=0;	
				
				//wait image preload for setup
				var imgLoad=new Image();
				imgLoad.onload=function(){
					MAIN_WIDTH=(MAIN_WIDTH==0 || isNaN(MAIN_WIDTH))?this.width:MAIN_WIDTH;
					MAIN_HEIGHT=(MAIN_HEIGHT==0 || isNaN(MAIN_HEIGHT))?this.height:MAIN_HEIGHT;
					$this.data('dims',{
						width:MAIN_WIDTH,
						height:MAIN_HEIGHT
					});
					$this.css({'width':MAIN_WIDTH,'height':MAIN_HEIGHT});
					container.css({'width':MAIN_WIDTH, 'height':MAIN_HEIGHT});
					windowDiv.css({'width':MAIN_WIDTH, 'height':MAIN_HEIGHT});
					setUpControls(controlsDiv);
				};
				imgLoad.src=this.src;			

				function setUpControls(elem)
				{
					var cpox=settings.controlsPos[0];
					var cpoy=settings.controlsPos[1];
					
					if(typeof(cpox)=='string')	cpox=(cpox=='right')?MAIN_WIDTH-elem.width():0;
					
					if(typeof(cpoy)=='string')	cpoy=(cpoy=='bottom')?MAIN_HEIGHT-elem.height():0;

					elem.css({'left':cpox,'top':cpoy});
				}
				//function to get MOUSEX, MOUSEY postiion relative to window
				//or to get touchx,y medium coordinates on more than one touche
				var TOUCHNUM=0;
				function medium_coors(e,rel2obj)
				{
					var fingers=1;
					if(rel2obj && (MAIN_TOP==0 || MAIN_LEFT==0))//calculate once for perfomance
					{
						MAIN_TOP=$(rel2obj).offset().top;
						MAIN_LEFT=$(rel2obj).offset().left;
					}
					
					if(touchy)//i consider only two touches for zooming
					{
						e=e.originalEvent;
						fingers=e.touches.length;
						var xsum=0,ysum=0;
						//calculate centroid for touches
						for(var i=0;i<fingers;i++)
						{
							xsum+=(e.touches[i].pageX-MAIN_LEFT);
							ysum+=(e.touches[i].pageY-MAIN_TOP);
						}
					
						return [xsum/fingers,ysum/fingers,fingers];
					}
					else
					{
						return [e.pageX-MAIN_LEFT,e.pageY-MAIN_TOP,fingers];
					}
				}
				/******************************************************/
				
				var ZOOM=false;
				var START_X=0;//start X move position
				var START_Y=0;//start Y move position
				
				var MOUSEX=0;//mouse X relative to window DIV
				var MOUSEY=0;//mouse Y relative to window DIV
				
				var CURR_Y=0;//curr pos of img relative to window DIV
				var CURR_X=0;//obiusvly is 0 0 at start
				var LIMIT_X=0,LIMIT_Y=0;
				
				$this.ENABLE_DRAG=false;
				//bind events to window div, not to image tag, we leave image tag free
				windowDiv
				.bind(down,function(e){
					e.preventDefault();
					//get starting position relative to windowDiv always
					CURR_X=$this.get(0).offsetLeft;
					CURR_Y=$this.get(0).offsetTop;
					MAIN_TOP=$(this).offset().top;
					MAIN_LEFT=$(this).offset().left;
					LIMIT_X=-MAIN_WIDTH*($this.zoomLevel-1);
					LIMIT_Y=-MAIN_HEIGHT*($this.zoomLevel-1);
					
					var coors=medium_coors(e,this);
					START_X=coors[0];
					START_Y=coors[1];
					MOUSEX=coors[0];
					MOUSEY=coors[1];
					TOUCHNUM=coors[2];

					//update and get image position, dimensions
					var coors=medium_coors(e,this);
					START_X=coors[0];
					START_Y=coors[1];
					TOUCHNUM=coors[2];
					if(e.shiftKey)
						$this.controlZoom(0.05);
					else if(e.altKey)
						$this.controlZoom(-0.05);
					else
						$this.ENABLE_DRAG=true;					
				})
				.bind(move,function(e)
				{
					//mouse move mouse coords and drag
					e.preventDefault();
					var coors=medium_coors(e,this);
					MOUSEX=coors[0];
					MOUSEY=coors[1];
					TOUCHNUM=coors[2];
					if(TOUCHNUM<=1 && $this.ENABLE_DRAG)	$this.drag();
				})
				.bind(end,function(e)
				{
					$this.ENABLE_DRAG=false;//clearTimeout(dragTimer);//clear timer on mouseup
					clearTimeout(zoomtimer);
				})
				.bind('dblclick',function(e)
				{
					if(!e.altKey && !e.shiftKey)
					{
						var coors=medium_coors(e,this);
						MOUSEX=coors[0];
						MOUSEY=coors[1];
						$this.zoomInOut(1);
					}
				})
				.bind('mousewheel',function(e,delta) 
				{ 
					if(settings.mousewheel)
					{
						e.preventDefault();
						if(settings.overrideMousewheel)		e.stopPropagation();
						$this.zoomInOut(delta/settings.sensivity);			
					}
				})
				.get(0).ongesturechange=function(e)//not support by android 2.x
				{
					e.preventDefault();
					if(TOUCHNUM==2)	
					{
						var delta=(e.scale<1)?-1:1;
						$this.zoomInOut(delta/settings.sensivity);
					}
					else if(TOUCHNUM>=3)
					{
						  $this.css('webkitTransform',  "rotate(" + ((e.rotation) % 360) + "deg)");
					}
				};
				
				
				$(document).bind(end,function(ev)
				{
					$this.ENABLE_DRAG=false;
					clearTimeout(zoomtimer);
				});
				
				$this.data('ENABLE-AXZ',true);
				$this.drag=function()//simple drag function, no jquery
				{
					if($this.data('ENABLE-AXZ')!=true)	return;
					var new_x=MOUSEX-START_X+CURR_X;
					var new_y=MOUSEY-START_Y+CURR_Y;
					//x:[LIMIT_X,0]		
					if(new_x<=0 && new_x>=LIMIT_X)	$this.css({'left':new_x});
					//x:[LIMIT_Y,0]
					if(new_y<=0 && new_y>=LIMIT_Y)	$this.css({'top':new_y});
				};			
												
				/******************************ZOOOM FUNCTIONS****************************/	
				$this.zoomLevel=1;
				var timeoutloader;
			    
				$this.zoomInOut=function (zoom)//zoom is fracntion, percent, not level
				{
					if($this.data('ENABLE-AXZ')!=true)	return;
				
					//calculate new dims
					var new_w=$this.width()*(1+zoom);
					if(new_w<=MAIN_WIDTH) new_w=MAIN_WIDTH;//limit dimension
					
					var new_h=$this.height()*(1+zoom);
					if(new_h<=MAIN_HEIGHT) new_h=MAIN_HEIGHT;
					
					var newzoom=new_w/MAIN_WIDTH;
					if(newzoom>>0<=settings.maxZoom)//a bit faster newzoom<<0 == Math.round(newzoom)
					{
						$this.zoomLevel=newzoom;
						//get current dims and position
						CURR_X=$this.get(0).offsetLeft;
						CURR_Y=$this.get(0).offsetTop;
						//calculate new position
						
						//limits for maintaing zoom inside window
						LIMIT_X=-MAIN_WIDTH*($this.zoomLevel-1);
						LIMIT_Y=-MAIN_HEIGHT*($this.zoomLevel-1);
						var new_x=zoom*(CURR_X-MOUSEX)+CURR_X;
						var new_y=zoom*(CURR_Y-MOUSEY)+CURR_Y;
						
						if(new_x<LIMIT_X) new_x=LIMIT_X;//limits
						if(new_x>=0) new_x=0;//in this case the limit is 0 but for multiple 3d images may have a limit
					
						if(new_y<=LIMIT_Y) new_y=LIMIT_Y;
						if(new_y>=0) new_y=0;//same
						
						$this.css({'width':new_w,'height':new_h,'top':new_y,'left':new_x});
					}		
					
					if($this.zoomLevel>1.2 && ZOOM_SRC!='' && ZOOM_SRC!=null && ZOOM_SRC!=$this.attr('src'))
					{
						var load=new Image();
						load.onload=function()
						{
							$this.attr('src',ZOOM_SRC);
						};
						load.src=ZOOM_SRC;
					}
				};
    	    });
		},
		enable:function()
		{
			return this.each(function()
			{
				var $this = $(this);
				$this.data('ENABLE-AXZ',true);
			});
		},
		disable:function()
		{
			return this.each(function()
			{
				var $this = $(this);
				$this.data('ENABLE-AXZ',false);
			});
		},
		destroy : function()
		{
			return this.each(function()
			{
				var $this = $(this);
				$this.removeData('settings').removeClass('ax-zoom');
				var old=$this.data('init-status');
				var dims=$this.data('dims');

				$this.css({'width':dims.width,'height':dims.height,'position':old.position,'left':old.left,'top':old.top}) //reset init css status
				     .appendTo(old.parent)//append to orginal parent
				     .attr('src',old.src)
				     .data('container').remove();//remove container created by plugin
				$(document).unbind('.axzoomer');//unbind events attach to document by plugin
			});
		},
		option : function(option, value)
		{
			return this.each(function(){
				var $this=$(this);
				var curr_setts=$this.data('settings');
				if(value!=null && value!=undefined)
				{
					curr_setts[option]=value;
					$this.data('settings',curr_setts);
				}
				else
					return curr_setts[option];
				
			});
		}
	};

	$.fn.axzoomer = function(method, options)
	{
		if(methods[method])
		{
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if(typeof method === 'object' || !method)
		{
			return methods.init.apply(this, arguments);
		}
		else
		{
			$.error('Method ' + method + ' does not exist on jQuery.zoomer3d');
		}
	};

})(jQuery);
