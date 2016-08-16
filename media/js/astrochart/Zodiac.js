(function(w){
        
	w.Zodiac = function () {
            var my = {},            // store private member variables and functions
                pub = w.Canvas();	// store public member variables and functions

            pub.defaultHeight = w._default.sign_size;    // default canvas width & height
            
            pub.rome_dgt = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
            
            pub.order = 0;                  // Sign number in Zodiac
            // default colors for signs. Should be rgb to programmatically turn them to rgba for bg.
//            pub.colorSet = ['rgb(204, 0, 0)', 'rgb(0, 0, 0)', 'rgb(0, 0, 255)', 'rgb(0, 102, 0)'];
            pub.colorSet = ['#C00', '#000', '#00F', '#060'];
            pub.altColorSet = ['#F7D900', '#A1A1A1', '#00D9FF', '#00E800'];
            pub.bg_colorSet = ['#FF0', '#bebebe', '#0FF', '#0F0'];    // default background colors for signs
            
            pub.ratio = 1;                  // Отношение ширины к высоте по умолчанию

            pub.spanRad = 30 * Math.PI / 180;   // Угол 30 градусов в радианах

            // Prepare template canvas for further processing.
            pub.makeCanvas = function (h, c, line) {
                h = ( typeof(h) !== 'undefined' ) ? h : pub.defaultHeight;
                c = ( typeof(c) !== 'undefined' ) ? c : pub.defaultColor();
                line = ( typeof(line) !== 'undefined' ) ? line : h / 10;

                var w = (pub.ratio > 0) ? pub.ratio * h : h;
                var h = (pub.ratio > 0) ? h : Math.abs(pub.ratio) * h;
                
                pub._makeCanvas(h, w, c, line);
            };
            
            /**
             * Single sign canvas with given id
             * @param {int} h height
             * @param {string} color
             * @param {int} line stroke width
             * @param {string} id of the node
             * @returns {canvas node}
             */
            pub.draw = function(h, color, line, id) {
                id = ( typeof(id) !== 'undefined' ) ? id : pub.name.toLowerCase();
                var sign = pub.drawSign(h, color, line);
                if ( id )
                    sign.setAttribute("id", id);
                pub.position();
                
                return sign;
            };
            
            pub.box_sign = function(h, color, line, palette) {
                // Defaults taken from the canvas context hence we need to draw sign first...
                var sign = pub.drawSign(h, color, line);
                
                // ...then defaults defined.
                var _palette = {backgroundColor: 'white',
                                border: 'thin solid ' + pub.ctx.strokeStyle,
                                borderRadius: pub.can.width / 2 + pub.ctx.lineWidth + 'px',
                                verticalAlign: 'middle',
                                padding: pub.ctx.lineWidth + 'px'};

                // If different data passed...
                $.extend(_palette, palette);
                var center = $('<center/>').append($(sign).css({margin: -pub.ctx.lineWidth + 'px 0 0 ' + -pub.ctx.lineWidth + 'px'}));
                return $('<div/>').css(_palette).append(center);
            };

            pub.position = function() {
                var x = w._default.radius + w._default.line - pub.can.width / 2 + (w._default.inner_ratio + 1) / 2 * w._default.radius * Math.cos(-pub.startAngle() - pub.spanRad / 2 - Math.PI);
                var y = w._default.radius + w._default.line - pub.can.height / 2 + (w._default.inner_ratio + 1) / 2 * w._default.radius * Math.sin(-pub.startAngle() - pub.spanRad / 2 - Math.PI);
                pub.can.style.left = Math.round(x)+'px';
                pub.can.style.top = Math.round(y)+'px';
            };
            
            pub.make_background = function(h,c,l) {
                c = c.replace('rgb', 'rgba').replace(')', ', .15)');
                var can = document.createElement('canvas'); // DOM canvas element
                var ctx = can.getContext("2d");             // Graphic driver
                can.width = can.height = h;
                ctx.strokeStyle = ctx.fillStyle = c;
                
                ctx.font = 'bold 120px Times New Roman';
                ctx.textAlign = 'center';
                ctx.fillText(pub.rome_dgt[pub.order], h / 2, h / 5 + 120 / 2, h);
                
                ctx.font = 'bold 30px Times New Roman';
                ctx.fillText(i18n.house, h / 2, h - .1 * h);
                
                return can;
            };
                        
            pub.defaultColor = function() {
                return pub.colorSet[(pub.order % 4)];
            };
            pub.altColor = function() {
                return pub.altColorSet[(pub.order % 4)];
            };
            pub.bgColor = function() {
                return pub.bg_colorSet[(pub.order % 4)];
            };
            pub.startAngle = function() {
                return pub.order*pub.spanRad;
            };
            
            return pub;
	};
	
	w.Aries = function() {
            var my = {},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Aries';
            pub.order = 0;
            pub.ruler = ['Mars'];
            pub.detriment = ['Venus'];
            pub.exalted = ['Sun'];
            pub.fall = ['Saturn'];


            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);
                pub.ctx.beginPath();
                pub.ctx.moveTo(pub.ctx.lineWidth / 2, 0.4 * pub.can.height);
                pub.ctx.bezierCurveTo(  0.3 * pub.can.width, -0.46 * pub.can.height,
                                        0.5 * pub.can.width, 0.5 * pub.can.height,
                                        0.5 * pub.can.width, pub.can.height );
                pub.ctx.bezierCurveTo(  0.5 * pub.can.width, 0.5 * pub.can.height,
                                        0.7 * pub.can.width, -0.46 * pub.can.height,
                                        pub.can.width - pub.ctx.lineWidth / 2, 0.4 * pub.can.height );

                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
	};
        
        w.Taurus = function() {
            var my = {r: .77},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Taurus';
            pub.order = 1;
            pub.ruler = ['Venus'];
            pub.detriment = ['Mars', 'Pluto'];
            pub.exalted = ['Moon'];
            pub.fall = ['Uran'];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);
                var radius = parseInt(pub.can.height / 3);
                var center = pub.can.height - radius - pub.ctx.lineWidth / 2;
                var _w = pub.can.width * my.r;

                pub.ctx.translate(pub.can.height * (1 - my.r) / 2, 0 );
                pub.ctx.beginPath();
                pub.ctx.arc(_w / 2, -pub.ctx.lineWidth / 2, radius, 0, Math.PI);
                pub.ctx.stroke();

                pub.ctx.beginPath();
                pub.ctx.arc(_w / 2, center, radius, 0, Math.PI * 2);
                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
	};
        
        w.Gemini = function() {
            var my = {r: .75},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Gemini';
            pub.order = 2;
            pub.ruler = ['Mercury'];
            pub.detriment = ['Jupiter'];
            pub.exalted = [];
            pub.fall = [];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);
                var radius = pub.can.height * 1.5;
                var centerY = 1.4 * pub.can.height;
                var _w = pub.can.width * my.r;

                pub.ctx.translate(pub.can.height * (1 - my.r) / 2, 0 );
                pub.ctx.beginPath();
                pub.ctx.arc(_w / 2, -centerY, radius, 77 * Math.PI / 180, 103 * Math.PI / 180);
                pub.ctx.stroke();

                pub.ctx.beginPath();
                pub.ctx.arc(_w / 2, pub.can.height + centerY, radius, 257 * Math.PI / 180, 283 * Math.PI / 180);

                pub.ctx.moveTo(0.35 * _w, 0.1 * pub.can.height);
                pub.ctx.lineTo(0.35 * _w, 0.9 * pub.can.height);
                pub.ctx.moveTo(0.65 * _w, 0.1 * pub.can.height);
                pub.ctx.lineTo(0.65 * _w, 0.9 * pub.can.height);
                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
	};
        
        w.Cancer = function() {
            var my = {r: .7},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Cancer';
            pub.order = 3;
            pub.ruler = ['Moon'];
            pub.detriment = ['Saturn'];
            pub.exalted = ['Jupiter'];
            pub.fall = ['Mars'];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);

                var radius = pub.can.width / 6;
                var _h = pub.can.width * my.r;
                var centerY = 0.4 * _h;

                pub.ctx.translate(0, pub.can.height * (1 - my.r) / 2 );
                pub.ctx.beginPath();
                pub.ctx.arc(radius + pub.ctx.lineWidth, centerY, radius, 0, 2 * Math.PI);
                pub.ctx.moveTo( radius + pub.ctx.lineWidth - radius * Math.sin(30 * Math.PI / 180),
                                centerY - radius * Math.cos(30 * Math.PI / 180));
                pub.ctx.bezierCurveTo(  radius + pub.ctx.lineWidth, centerY - radius - pub.ctx.lineWidth / 2,
                                        pub.can.width / 2, 0,
                                        pub.can.width - pub.ctx.lineWidth / 2, centerY - radius * Math.cos(30 * Math.PI / 180));

                pub.ctx.stroke();

                pub.ctx.beginPath();
                pub.ctx.arc(pub.can.width - (radius + pub.ctx.lineWidth), _h - centerY, radius, 0, 2 * Math.PI);
                pub.ctx.moveTo( pub.can.width - radius - pub.ctx.lineWidth + radius * Math.sin(30 * Math.PI / 180),
                                _h - centerY + radius * Math.cos(30 * Math.PI / 180));
                pub.ctx.bezierCurveTo(  pub.can.width - radius - pub.ctx.lineWidth, _h - centerY + radius + pub.ctx.lineWidth / 2,
                                        pub.can.width / 2, _h,
                                        pub.ctx.lineWidth / 2, _h - centerY + radius * Math.cos(30 * Math.PI / 180));

                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
	};
        
        w.Leo = function() {
            var my = {r: .65},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Leo';
            pub.order = 4;
            pub.ruler = ['Sun'];
            pub.detriment = ['Saturn', 'Uran'];
            pub.exalted = ['Pluto'];
            pub.fall = ['Mercury'];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);

                var radius = pub.can.height * 0.16;
                var centerY = 0.57 * pub.can.height + pub.ctx.lineWidth / 2;

                pub.ctx.translate(pub.can.width * (1 - my.r) / 2, 0);
                pub.ctx.beginPath();
                pub.ctx.arc(radius, centerY, radius, 0, 2 * Math.PI);
                pub.ctx.moveTo(2 * radius, centerY);
                pub.ctx.bezierCurveTo(  2 * radius, centerY - radius,
                                        radius - pub.ctx.lineWidth / 2, 0.14 * pub.can.height + (pub.ctx.lineWidth - .1 * pub.can.height),
                                        2 * radius, 0.07 * pub.can.height + (pub.ctx.lineWidth - .1 * pub.can.height));
                pub.ctx.bezierCurveTo(  5 * radius, -pub.ctx.lineWidth,
                                        radius, pub.can.height,
                                        pub.can.width * my.r, pub.can.height * .95);

                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
	};
        
        w.Virgin = function() {
            var my = {},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Virgin';
            pub.order = 5;
            pub.ruler = ['Mercury'];
            pub.detriment = ['Jupiter', 'Neptune'];
            pub.exalted = [];
            pub.fall = ['Venus'];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);
                var step = pub.can.width / 4.25;

                pub.ctx.beginPath();
                pub.ctx.moveTo(0, pub.ctx.lineWidth / 2);
                pub.ctx.bezierCurveTo(  step * 0.75, pub.ctx.lineWidth / 2,
                                        step * 0.75, pub.ctx.lineWidth / 2,
                                        step * 0.75, pub.can.height - step);

                pub.ctx.moveTo(step * 0.7, step);
                pub.ctx.bezierCurveTo(  step * 1.75, -pub.ctx.lineWidth,
                                        step * 1.75, pub.ctx.lineWidth / 2,
                                        step * 1.75, pub.can.height - step);

                pub.ctx.moveTo(1.7 * step, step);
                pub.ctx.bezierCurveTo(  step * 2.75, -pub.ctx.lineWidth,
                                        step * 2.75, pub.ctx.lineWidth / 2,
                                        step * 2.75, pub.can.height - step);
                pub.ctx.bezierCurveTo(  step * 2.75, pub.can.height - pub.ctx.lineWidth,
                                        step * 3.25, pub.can.height - pub.ctx.lineWidth / 2,
                                        step * 3.75, pub.can.height - pub.ctx.lineWidth / 2);

                pub.ctx.moveTo(step * 2.75, step * 2);
                pub.ctx.bezierCurveTo(  pub.can.width, pub.ctx.lineWidth,
                                        pub.can.width + pub.ctx.lineWidth / 2, pub.can.height - pub.ctx.lineWidth / 2,
                                        step * 1.75, pub.can.height - pub.ctx.lineWidth / 2);

                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
        };
                
        w.Libra = function() {
            var my = {r: .65},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Libra';
            pub.order = 6;
            pub.ruler = ['Venus'];
            pub.detriment = ['Mars'];
            pub.exalted = ['Saturn'];
            pub.fall = ['Sun'];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);

                pub.ctx.beginPath();
                pub.ctx.translate(0, pub.can.height * (1 - my.r) / 2 );
                pub.ctx.moveTo(0, pub.can.width * (1 - my.r));
                pub.ctx.lineTo(pub.can.width * .3, pub.can.width * (1 - my.r));
                pub.ctx.bezierCurveTo(  -.1 * pub.can.width, -.06 * pub.can.width,
                                        1.1 * pub.can.width, -.06 * pub.can.width,
                                        pub.can.width * .7, pub.can.width * (1 - my.r));
                pub.ctx.lineTo(pub.can.width, pub.can.width * (1 - my.r));

                pub.ctx.moveTo(0, pub.can.width * (1 - my.r) + pub.ctx.lineWidth * 2);
                pub.ctx.lineTo(pub.can.width, pub.can.width * (1 - my.r) + pub.ctx.lineWidth * 2);
                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
	};
        
        w.Scorpio = function() {
            var my = {},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Scorpio';
            pub.order = 7;
            pub.ruler = ['Mars', 'Pluto'];
            pub.detriment = ['Venus'];
            pub.exalted = ['Uran'];
            pub.fall = ['Moon'];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);
                var step = pub.can.width / 4.25;

                pub.ctx.beginPath();
                pub.ctx.moveTo(0, pub.ctx.lineWidth / 2);
                pub.ctx.bezierCurveTo(  step * 0.75, pub.ctx.lineWidth / 2,
                                        step * 0.75, pub.ctx.lineWidth / 2,
                                        step * 0.75, pub.can.height - step);

                pub.ctx.moveTo(step * 0.7, step);
                pub.ctx.bezierCurveTo(  step * 1.75, -pub.ctx.lineWidth,
                                        step * 1.75, pub.ctx.lineWidth / 2,
                                        step * 1.75, pub.can.height - step);

                pub.ctx.moveTo(1.7 * step, step);
                pub.ctx.bezierCurveTo(  step * 2.75, -pub.ctx.lineWidth,
                                        step * 2.75, pub.ctx.lineWidth / 2,
                                        step * 2.75, pub.can.height - step * 1.5);
                pub.ctx.bezierCurveTo(  step * 2.75, pub.can.height - pub.ctx.lineWidth,
                                        step * 3.25, pub.can.height - pub.ctx.lineWidth,
                                        step * 3.75, pub.can.height - pub.ctx.lineWidth);
                pub.ctx.stroke();

                pub.ctx.beginPath();
                pub.ctx.moveTo(step * 3.7, pub.can.height);
                pub.ctx.lineTo(pub.can.width, pub.can.height - pub.ctx.lineWidth);
                pub.ctx.lineTo(step * 3.7, pub.can.height - pub.ctx.lineWidth * 2);
                pub.ctx.closePath();
                pub.ctx.fill();

                return pub.can;
            };

            return pub;
        };
        
        w.Sagittatius = function() {
            var my = {r: .75},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Sagittatius';
            pub.order = 8;
            pub.ruler = ['Neptune', 'Jupiter'];
            pub.detriment = ['Mercury'];
            pub.exalted = [];
            pub.fall = [];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);
                var step = 0.4 * pub.can.height;
                var width = pub.can.width * my.r;

                pub.ctx.translate(pub.can.height * (1 - my.r) / 2, 0 );
                pub.ctx.beginPath();
                pub.ctx.moveTo(width - step, pub.ctx.lineWidth / 2);
                pub.ctx.lineTo(width, pub.ctx.lineWidth / 2);
                pub.ctx.moveTo(width - pub.ctx.lineWidth / 2, 0);
                pub.ctx.lineTo(width - pub.ctx.lineWidth / 2, step);
                pub.ctx.moveTo(pub.ctx.lineWidth * 0.4, pub.can.height - pub.ctx.lineWidth * 0.4);
                pub.ctx.lineTo(width - pub.ctx.lineWidth * 0.4, 0);
                pub.ctx.moveTo(pub.can.height * 0.2, pub.can.height * 0.364);
                pub.ctx.lineTo(pub.can.height * 0.5525, pub.can.height * 0.636);
                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
	};
        
        w.Capricorn = function() {
            var my = {r: .17647},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Capricorn';
            pub.order = 9;
            pub.ruler = ['Uran', 'Saturn'];
            pub.detriment = ['Moon'];
            pub.exalted = ['Mars'];
            pub.fall = ['Neptune', 'Jupiter'];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);
                var step = pub.can.width * my.r;

                pub.ctx.translate(step, 0);
                pub.ctx.beginPath();
                pub.ctx.moveTo(0, pub.ctx.lineWidth / 2);
                pub.ctx.bezierCurveTo(  pub.ctx.lineWidth / 2, pub.ctx.lineWidth / 2,
                                        step * 0.75, pub.ctx.lineWidth / 2,
                                        step * 0.75, pub.can.height * 0.53);

                pub.ctx.moveTo(step * 0.7, step);
                pub.ctx.bezierCurveTo(  step * 2, -0.75 * pub.ctx.lineWidth,
                                        step * 2.8, pub.ctx.lineWidth * 1.4,
                                        step * 2, pub.can.height * 0.5);
                pub.ctx.bezierCurveTo(  step * 1.7, pub.can.height * 0.75,
                                        step * 3.25, pub.can.height * 0.75,
                                        step * 3.45, pub.can.height * 0.615);
                pub.ctx.bezierCurveTo(  pub.can.width, pub.can.height * 0.4,
                                        step * 2, pub.can.height * 0.4,
                                        step * 2, pub.can.height * 0.6);
                pub.ctx.bezierCurveTo(  step * 2, pub.can.height * 0.75,
                                        step * 2.2, pub.can.height - pub.ctx.lineWidth / 2,
                                        step, pub.can.height - pub.ctx.lineWidth / 2);

                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
        };
        
        w.Aquarius = function() {
            var my = {r: .7},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Aquarius';
            pub.order = 10;
            pub.ruler = ['Saturn', 'Uran'];
            pub.detriment = ['Sun'];
            pub.exalted = ['Neptune'];
            pub.fall = ['Pluto'];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);
                var x1u = pub.can.width * 0.212;
                var x1d = pub.ctx.lineWidth * 0.35;
                
                var y1u = pub.can.width * 0.08;
                var y1d = pub.can.width * 0.223;
                
                var sX = pub.can.width * 0.23;
                var sY = pub.can.width * 0.37;

                pub.ctx.beginPath();
                pub.ctx.translate(0, pub.can.height * (1 - my.r) / 2);
                pub.ctx.moveTo(x1d, y1d);
                pub.ctx.lineTo(x1u, y1u);
                pub.ctx.lineTo(x1d + sX, y1d);
                pub.ctx.lineTo(x1u + sX, y1u);
                pub.ctx.lineTo(x1d + sX * 2, y1d);
                pub.ctx.lineTo(x1u + sX * 2, y1u);
                pub.ctx.lineTo(x1d + sX * 3, y1d);
                pub.ctx.lineTo(x1u + sX * 3, y1u);
                pub.ctx.lineTo(x1d + sX * 4, y1d);
                
                pub.ctx.moveTo(x1d, y1d + sY);
                pub.ctx.lineTo(x1u, y1u + sY);
                pub.ctx.lineTo(x1d + sX, y1d + sY);
                pub.ctx.lineTo(x1u + sX, y1u + sY);
                pub.ctx.lineTo(x1d + sX * 2, y1d + sY);
                pub.ctx.lineTo(x1u + sX * 2, y1u + sY);
                pub.ctx.lineTo(x1d + sX * 3, y1d + sY);
                pub.ctx.lineTo(x1u + sX * 3, y1u + sY);
                pub.ctx.lineTo(x1d + sX * 4, y1d + sY);
                
                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
	};
        
        w.Pisces = function() {
            var my = {r:.75},		// more private vars and functions will live here
                pub = w.Zodiac();	// and here we subclass parent Zodiac class

            pub.name = 'Pisces';
            pub.order = 11;
            pub.ruler = ['Jupiter', 'Neptune'];
            pub.detriment = ['Mercury'];
            pub.exalted = ['Venus'];
            pub.fall = [];

            pub.drawSign = function(h, color, line) {
                pub.makeCanvas(h, color, line);

                pub.ctx.translate(pub.can.height * (1 - my.r) / 2, 0 );
                pub.ctx.save();
                pub.ctx.beginPath();
                pub.ctx.arc(-pub.can.height * 0.47, pub.can.height / 2, pub.can.width * my.r, -40 * Math.PI / 180, 40 * Math.PI / 180);
                pub.ctx.stroke();
                
                pub.ctx.beginPath();
                pub.ctx.arc(pub.can.height * 1.22, pub.can.height / 2, pub.can.width * my.r, 140 * Math.PI / 180, 220 * Math.PI / 180);
                pub.ctx.moveTo(0, pub.can.height / 2);
                pub.ctx.lineTo(pub.can.width * my.r, pub.can.height / 2);
                pub.ctx.stroke();

                return pub.can;
            };

            return pub;
	};
        
}(window));