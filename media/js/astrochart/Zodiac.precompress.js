(function(w){
    w._default = {
                radius: 358,
                inner_ratio: 0.8883,
                center_ratio: 0.5587,
                line: 2,
                sign_size: 25,
                highlight_color: '#F819F8',
                container_class: 'chart',
                modal_dialog_width: 250
            };
    
    w.Chart = function() {
        var self = this;

        self.zodiak = ['aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgin', 'libra', 'scorpio', 'sagittatius', 'capricorn', 'aquarius', 'pisces'];
        self.container = document.getElementsByClassName(w._default.container_class)[0];
        self.container.style.minHeight = w._default.radius * 2 + w._default.sign_size + 'px';

        self.drawSigns = function() {
            var xtx;
            var svg = document.createElement('svg');    // svg node for element
            self.zodiak.forEach(function(el){
                var sign = new w[el.capitalize()];
                xtx = sign.draw();
                xtx.className = 'sign';
                
                svg.appendChild(xtx);
            });

            self.container.appendChild(svg);
        };

        self.drawChart = function() {
            var svg = document.createElement('svg');    // svg node for element

            var half_size = w._default.radius + w._default.line;
            var inner_r = w._default.inner_ratio * w._default.radius;
            var center_r = w._default.center_ratio * w._default.radius;

            var can = document.createElement('canvas'); // DOM canvas element
            var ctx = can.getContext("2d");             // Graphic driver

            can.width = can.height = 2*half_size;
            ctx.strokeStyle = '#000';
            ctx.lineWidth = w._default.line;

            ctx.beginPath();
            ctx.arc(half_size,
                    half_size,
                    w._default.radius * w._default.center_ratio,
                    0,
                    2 * Math.PI);

            ctx.fillStyle = '#FFF';
            ctx.fill();

            ctx.arc(half_size,
                    half_size,
                    w._default.radius * w._default.inner_ratio,
                    0,
                    2 * Math.PI);

            ctx.arc(half_size,
                    half_size,
                    w._default.radius,
                    0,
                    2 * Math.PI);
            self.zodiak.forEach(function(el){
                    var Sign = w[el.capitalize()]();
                    var _a = - Math.PI - Sign.startAngle();
                    var xs = w.Sector(Sign).draw();
                    svg.appendChild(xs[0]);
                    svg.appendChild(xs[1]);

                    ctx.moveTo(half_size + w._default.radius * Math.cos(_a),
                               half_size + w._default.radius * Math.sin(_a));
                    ctx.lineTo(half_size + center_r * Math.cos(_a),
                               half_size + center_r * Math.sin(_a));
            });        
            ctx.stroke();
            can.className = 'frame';
            svg.appendChild(can);

            self.container.appendChild(svg);
        };

        self.markDegree = function() {
            var _size = w._default.radius + w._default.line;

            for (var i = 0; i < 360; i += 30) {
                var deg = document.createElement('div');
                deg.className = 'degree_'+i;
                deg.innerHTML = i+'<sup><small>0</small></sup>';
                self.container.appendChild(deg);

                var _w = $('div[class^="degree_"]').width();
                var _h = $('div[class^="degree_"]').height();

                var def = Math.cos((i * Math.PI) / 180 - Math.PI);
                var pos = Math.round(_size * (1 + def));

                pos -= def > 0.02 
                                ? 0 
                                : def < -0.02
                                    ? _w 
                                    : _w / 2;
                deg.style.left = pos+'px';

                def = Math.sin((i * Math.PI) / 180 - Math.PI);

                pos = Math.round(_size * (1 - def));
                pos -= def > -0.02 
                                ? _h
                                : 0;
                deg.style.top = pos+'px';
            }
        };

        self.run = function() {

            self.drawChart();
            self.markDegree();
            self.drawSigns();

            Aspect().draw_all(); 
        };

        return self;
    };
        
    w.Canvas = function() {
        // store private and public member variables and functions
        var my = {},	
            pub = {};

        pub.colorSet = {"green":'#060', "red":'#F00', "black": '#000'};    // default colors for planets

        pub.can = document.createElement('canvas'); // DOM canvas element
        pub.ctx = pub.can.getContext("2d");         // Graphic driver

        // Prepare template canvas for further processing.
        pub._makeCanvas = function (h, w, c, line) {
            // If no dimensions, then defaults used. 
            pub.can.width = ( typeof(w) !== 'undefined' ) ? w : ( typeof(pub.defaultWidth) !== 'undefined' ) ? pub.defaultWidth : w._default.sign_size;
            pub.can.height = ( typeof(h) !== 'undefined' ) ? h : ( typeof(pub.defaultHeight) !== 'undefined' ) ? pub.defaultHeight : w._default.sign_size;

            if ( typeof(c) !== 'undefined' ) {
                pub.ctx.strokeStyle = c;
                pub.ctx.fillStyle = c;
            }
            if ( typeof(line) !== 'undefined' )
                pub.ctx.lineWidth = line;
        };

        pub.getColor = function(c) {
        return typeof(c) !== 'undefined' 
                            ? c in pub.colorSet 
                                        ? pub.colorSet[c] 
                                        : c.is_color()
                                            ? c
                                            : pub.colorSet.black
                            : pub.colorSet.black;
    };

        return pub;
    };
        
    w.Sector = function( Sign ) {
        var self = this;

        var inner_r = w._default.inner_ratio*w._default.radius;

        self.can = [document.createElement('canvas'), document.createElement('canvas')]; // DOM canvas element
        // Graphic drivers
        self.ctx_0 = self.can[0].getContext("2d");         
        self.ctx_1 = self.can[1].getContext("2d");

        // Prepare template canvas for further processing.
        self.makeCanvas = function () {
            self.can[0].width = self.can[0].height = self.can[1].width = self.can[1].height = 2*(w._default.radius + w._default.line);
            self.ctx_0.strokeStyle = self.ctx_1.strokeStyle = '#000';
            self.ctx_0.fillStyle = Sign.bgColor();
            self.ctx_1.fillStyle = w._default.highlight_color;
            self.ctx_0.lineWidth = self.ctx_1.lineWidth = w._default.line;
        };

        self.draw = function() {
            self.ctx_0.beginPath();
            self.ctx_0.arc( self.can[0].width / 2,
                            self.can[0].width / 2,
                            w._default.radius,
                            -Sign.startAngle() - Math.PI - Sign.spanRad,
                            -Sign.startAngle() - Math.PI);
            self.ctx_0.lineTo(self.can[0].width / 2 + inner_r * Math.cos(-Sign.startAngle() - Math.PI),
                              self.can[0].width / 2 + inner_r * Math.sin(-Sign.startAngle() - Math.PI));
            self.ctx_0.arc( self.can[0].width / 2,
                            self.can[0].width / 2,
                            inner_r,
                            -Sign.startAngle() - Math.PI,
                            -Sign.startAngle() - Math.PI - Sign.spanRad,
                            true);
            self.ctx_0.closePath();

            self.ctx_0.fill();
            self.can[0].className = 'elem_' + Sign.order % 4;

            self.ctx_1.beginPath();
            self.ctx_1.arc( self.can[0].width / 2,
                            self.can[0].width / 2,
                            w._default.radius,
                            -Sign.startAngle() - Math.PI - Sign.spanRad,
                            -Sign.startAngle() - Math.PI);
            self.ctx_1.lineTo(self.can[0].width / 2,
                              self.can[0].width / 2);
            self.ctx_1.closePath();
            self.ctx_1.fill();
            self.can[1].className = 'hidden hl_' + Sign.order;

            return self.can;
        };

        self.makeCanvas();
        return self;
    };
        
    w.info_window = function() {
        var self = this;
        
        self._modal = $('#myModal');
        self._modal_body = self._modal.find('.modal-body');
        self._modal_container = $('<div/>', {id: 'info_container'});
        self._modal.find('button.toggle_zoom').remove();
        self._modal_body.find('img').replaceWith(self._modal_container);
        self._modal.find('.modal-dialog').width(w._default.modal_dialog_width);
        self.bg_size = 150; // Background canvas size
        self.bg_line = 20;  // Background canvas line width
        

        self.init = function($btn) {
            // Make instanse from button ID
            var id = $btn.attr('id');
            var parts = id.split(' ');
            var inst  = parts.length > 1 
                                    ? w[parts[0].capitalize()](parts[1].replace('_', '') === 'asc')
                                    : w[parts[0].replace('_', '').capitalize()]();
            
            // Variables
            var host, color, bg_color, sign, palette, bg_canvas,
                _h = 30, _p = 0, _l = 3, 
                _planets = ['ruler','detriment','exalted','fall'];
           
            $btn.is('.sign')
                ? function(){host = inst; _p = _l * 1.5; color = inst.defaultColor();}()
                : function(){host = inst.get_host(); color = $btn.is('.red') ? inst.colorSet.red : inst.colorSet.green;}();
            
            // Styles
            palette = { backgroundColor: 'white',
                        border: 'thin solid ' + color,
                        borderRadius: _h / 2 + _l + 'px',
                        width: _h + _l * 1.5 + 'px',
                        height: _h + _l * 1.5 + 'px',
                        marginRight: 2 * _l + 'px',
                        padding: _p + 'px',
                        float: 'left'};
                        
            bg_color = host.bgColor();
            
            // Sign for header of the info window
            sign = inst.box_sign(_h, color, _l, palette);
            // Background watermark
            bg_canvas = inst.make_background(self.bg_size, host.altColor(), self.bg_line);
            
            // Apply styles to window body and the container
            self._modal_body.css({backgroundColor: bg_color, border: '2px solid '+color});
            self._modal_container.css({ border: '1px dotted '+color,
                                        background: 'url(' + bg_canvas.toDataURL() + ') no-repeat center bottom',
                                        padding: '5px'}).html('');
            // Append header
            self._modal_container.append(sign).append($('<h3>', {css:{color: color}}).text(i18n[inst.name])).append($('<span/>', {class: 'clearfix'}));
            // Make rows and fill them. Insert rows into the container div.
            $.each(_planets, function(){
                var pl = this.toString();
                var col = pl === 'ruler' || pl === 'exalted' ? 'green' : 'red';
                var row = $('<div/>', {css: { borderTop: '1px dotted ' + color,
                                    color: col},
                             html: '<span>'+i18n[pl]+'</span>'} );
                         console.log(host[pl].length);
                if (host[pl].length) {
                    $.each(host[pl], function(){
                        $(w[this.toString()]().draw(20, col, 2)).addClass('pull-xs-right').appendTo(row.find('span'));
                    });
                }
                else {
                    $('<span/>',{class:'pull-xs-right',css:{color:col,fontWeight:'bold'},html:'&mdash;'}).appendTo(row.find('span'));
                }
                row.appendTo(self._modal_container);
            });
            // Time to show the window
            self._modal.modal('show');
        };
        
        return self;
    };
        
    String.prototype.capitalize = function() {
            return this.charAt(0).toUpperCase() + this.slice(1);
        };
    String.prototype.is_color = function() {
            return /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(this) || this.toLowerCase().indexOf('rgb') === 0;
        };
    w.Zodiac = function () {
        var my = {},            // store private member variables and functions
            pub = w.Canvas();	// store public member variables and functions

        pub.defaultHeight = w._default.sign_size;    // default canvas width & height

        pub.rome_dgt = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];

        pub.order = 0;                  // Sign number in Zodiac
        // default colors for signs. Should be rgb to programmatically turn them to rgba for bg.
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
    
    w.Aspect = function() {
        var my = {},            // store private member variables and functions
            pub = w.Canvas();   // store public member variables and functions
        
        my.aspects = ['join', 'sextile', 'quadrat', 'trigoni', 'opposition', 'semisextile', 'angle', 'one_and_half_q', 'quincunx'];
        my.defaultSignHeight = 20;
        
        // Start point is same for all aspects.
        pub.y0 = w._default.radius;
        pub.x0 = pub.y0 * (1 - w._default.center_ratio) + w._default.line;
        pub.colorSet = {"green":'#008000', "red":'#F00', "black": '#000'};
        
        pub.get_all = function() {
            return my.aspects;
        };
        
        pub.get_host = function() {
            return w[(Chart().zodiak[Math.floor(pub.angle / 30)]).capitalize()]();
        };
        
        pub.draw_all = function() {
            pub.drawDot();
            
            // Most aspects are twin, i.e. ascending (default) and descending (with false param).
            my.aspects.forEach (function(asp){
                    asp = asp.capitalize();
                    w[asp]().addAspect();
                    
                    if (asp !== 'Join' & asp !== 'Opposition')
                        w[asp](false).addAspect();
                });
        };
        
        // Prepare template canvas for further processing.
        pub.makeCanvas = function (h, c, line) {
            var w = h = ( typeof(h) !== 'undefined' ) ? h : my.defaultSignHeight;
            
            if (typeof(c) === 'undefined')
                c = pub.cls.indexOf('red') > 0 ? 'red' : 'green';
            
            c = pub.getColor(c);
            
            line = ( typeof(line) !== 'undefined' ) ? line : h / 10;

            pub._makeCanvas(h, w, c, line);
        };
                
        pub.createLine = function(x1,y1, x2,y2) {
            var length = Math.round(Math.sqrt((x1-x2)*(x1-x2) + (y1-y2)*(y1-y2)));
            var angle  = Math.atan2(y2 - y1, x2 - x1) * 180 / Math.PI;
            var transform = 'rotate('+angle+'deg)';

            var line = $('<div>')
                .css({
                  'transform': transform
                })
                .width(length)
                .offset({left: Math.round(x1), top: Math.round(y1)});

            return line;
        };
        
        pub.drawLine = function() {
            pub.end_x = pub.y0 - pub.y0 * w._default.center_ratio * Math.cos(pub.angle * Math.PI / 180);
            
            if ( pub.angle > 180 )
                pub.end_x += w._default.line;
            else if (pub.angle === 0)
                pub.end_x = -w._default.line * 10;
            
            pub.end_y = pub.y0 + pub.y0 * w._default.center_ratio * Math.sin(pub.angle * Math.PI / 180);
            
            var line = pub.createLine(pub.x0,pub.y0, pub.end_x, pub.end_y);
            
            line.addClass('line' + pub.cls + ' ' + pub.name)
                .attr('id', pub.name + '_')
                .attr('title', i18n[pub.name]);
            if ( pub.cls.indexOf('major') > 0 )
                line.css({'backgroundColor': line.is('.red') ? 'red' : 'green'});
            
            line.appendTo($('.' + w._default.container_class));
            line.hover(pub.on_hover);
        };
        
        pub.drawDot = function() {
            $('<div>')
                .addClass('dot')
                .width(w._default.line * 4)
                .height(w._default.line * 4)
                .offset({left: pub.x0 - 2 * w._default.line, top: pub.y0 - 2 * w._default.line})
                .appendTo($('.' + w._default.container_class));
        };
        
        pub.box_sign = function(h, color, line, palette) {
            // Defaults taken from the canvas context hence we need to draw sign first...
            var $sign = $(pub.drawSign(h, color, line));
            
            // ...then defaults defined.
            var _palette = {backgroundColor: 'white',
                            border: 'thin solid ' + pub.ctx.strokeStyle,
                            borderRadius: pub.can.width / 2 + pub.ctx.lineWidth + 'px',
                            padding: pub.ctx.lineWidth + 'px'};
                        
            // If different data passed...
            $.extend(_palette, palette);
            
            // Apply styles
            $sign.css(_palette);
                    
            return $sign;
        };
        
        pub.addAspect = function(h, color, line) {
            pub.drawLine();
            var $sign = pub.box_sign(h, color, line);
            $sign.css({ "left": pub.sign_left,
                        "top": pub.sign_top  });
            $sign.attr('id', pub.name)
                 .attr('title', i18n[pub.name]);
            $sign.addClass('aspect' + pub.cls + ' ' + pub.name).appendTo($('.' + w._default.container_class));
            $sign.hover(pub.on_hover);
            
        };
        
        pub.on_hover = function() {
            $( '.' + pub.name.replace(' ','.') ).toggleClass('hover');
        };
        
        pub.make_background = function(h,c,l) {
            c = c.replace('rgb', 'rgba').replace(')', ', .15)');
            return pub.get_host().drawSign(h,c,l);
        };
        
        return pub;
    };
    
    w.Join = function() {
        var my = {r: .23},            // store private member variables and functions
            pub = w.Aspect();   // store public member variables and functions
            
        pub.name = 'join';
        pub.angle = 0;
        pub.y0 -= w._default.line * 1.5;
        pub.cls = ' major red';
        
        pub.drawSign = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            
            pub.sign_left = (pub.x0 + pub.end_x) / 2 - pub.can.width / 2 - line + 'px';
            pub.sign_top = pub.y0 - pub.can.height / 2 + 'px';
            
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 4 + line,
                        pub.can.height / 4 * 3 - line,
                        my.r * pub.can.width,
                        0,
                        2*Math.PI);
            pub.ctx.stroke();
            
            pub.ctx.beginPath();
            pub.ctx.moveTo(pub.can.width - Math.sqrt(line * line / 2), Math.sqrt(line * line / 2));
            pub.ctx.lineTo(pub.can.width / 4 + line + pub.can.width * my.r * Math.cos(Math.PI / 4),
                           pub.can.height / 4 * 3 - line - pub.can.height * my.r * Math.cos(Math.PI / 4));
            pub.ctx.stroke();
            
            return pub.can;
        };
        
        return pub;
    };
    
    w.Opposition = function() {
        var my = {r: .18},            // store private member variables and functions
            pub = w.Aspect();   // store public member variables and functions
            
        pub.name = 'opposition';
        pub.angle = 180;
        pub.cls = ' major red';
        
        pub.drawSign = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            
            pub.sign_left = pub.end_x * .85 + 'px';
            pub.sign_top = pub.y0 - pub.can.height / 2 + 'px';
            
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2 - (pub.can.width / 2 - my.r * pub.can.width - line / 2) * Math.cos(Math.PI / 4),
                        pub.can.height / 2 + (pub.can.height / 2 - my.r * pub.can.height - line / 2) * Math.sin(Math.PI / 4),
                        my.r * pub.can.width,
                        0,
                        2*Math.PI);
            pub.ctx.stroke();
            
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2 + (pub.can.width / 2 - my.r * pub.can.width - line / 2) * Math.cos(Math.PI / 4),
                        pub.can.height / 2 - (pub.can.height / 2 - my.r * pub.can.height - line / 2) * Math.sin(Math.PI / 4),
                        my.r * pub.can.width,
                        0,
                        2*Math.PI);
            pub.ctx.stroke();
            
            pub.ctx.beginPath();
            pub.ctx.moveTo(pub.can.width / 2 + (pub.can.width / 2 - my.r * pub.can.width * 2 - line) * Math.cos(Math.PI / 4),
                           pub.can.height / 2 - (pub.can.height / 2 - my.r * pub.can.height * 2 - line) * Math.sin(Math.PI / 4));
            pub.ctx.lineTo(pub.can.width / 2 - (pub.can.width / 2 - my.r * pub.can.width * 2 - line) * Math.cos(Math.PI / 4),
                        pub.can.height / 2 + (pub.can.height / 2 - my.r * pub.can.height * 2 - line) * Math.sin(Math.PI / 4));
            pub.ctx.stroke();
            
            return pub.can;
        };
        
        return pub;
    };
    
    w.Sextile = function(asc) {
        asc = ( typeof(asc) !== 'undefined' ) ? asc : true; // Ascending (default) or descending aspect
        var my = {},            // store private member variables and functions
            pub = w.Aspect();   // store public member variables and functions
            
        pub.name = asc ? 'sextile asc' : 'sextile desc';
        pub.cls = ' major green';
        pub.angle = asc ? 60 : 300;
        
        pub.drawSign = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            
            pub.sign_left = pub.x0 + .85 * w._default.center_ratio * w._default.radius * Math.abs(Math.cos(pub.angle * Math.PI / 180)) - pub.can.width / 2 - line + 'px';
            pub.sign_top = pub.y0 + .85 * w._default.center_ratio * w._default.radius * Math.sin(pub.angle * Math.PI / 180) - pub.can.height / 2 + 'px';
            
            pub.ctx.beginPath();
            
            pub.ctx.moveTo(0, pub.can.height / 2);
            pub.ctx.lineTo(pub.can.width, pub.can.height / 2);
            pub.ctx.translate(pub.can.width / 2, pub.can.height / 2 );
            pub.ctx.rotate(Math.PI / 3);
            pub.ctx.translate(-pub.can.width / 2, -pub.can.height / 2 );
            pub.ctx.moveTo(0, pub.can.height / 2);
            pub.ctx.lineTo(pub.can.width, pub.can.height / 2);
            pub.ctx.translate(pub.can.width / 2, pub.can.height / 2 );
            pub.ctx.rotate(Math.PI / 3);
            pub.ctx.translate(-pub.can.width / 2, -pub.can.height / 2 );
            pub.ctx.moveTo(0, pub.can.height / 2);
            pub.ctx.lineTo(pub.can.width, pub.can.height / 2);
            pub.ctx.stroke();
            
            return pub.can;
        };
        
        return pub;
    };
    
    w.Quadrat = function(asc) {
        asc = ( typeof(asc) !== 'undefined' ) ? asc : true; // Ascending (default) or descending aspect
        var my = {},            // store private member variables and functions
            pub = w.Aspect();   // store public member variables and functions
            
        pub.name = asc ? 'quadrat asc' : 'quadrat desc';
        pub.angle = asc ? 90 : 270;
        pub.cls = ' major red';
        
        pub.drawSign = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            h = (pub.can.width - line) * Math.cos(Math.PI / 4);
            pub.sign_left = pub.x0 + .85 * w._default.center_ratio * w._default.radius - pub.can.width / 2 - line + 'px';
            pub.sign_top = asc 
                            ? pub.y0 + .85 * w._default.center_ratio * w._default.radius - pub.can.height / 2 + 'px'
                            : pub.y0 - .85 * w._default.center_ratio * w._default.radius - pub.can.height / 2 + 'px';
            
            pub.ctx.beginPath();
            
            pub.ctx.rect((pub.can.width - h) / 2,(pub.can.height - h) / 2,h,h);
            pub.ctx.stroke();
            
            return pub.can;
        };
        
        return pub;
    };
    
    w.Trigoni = function(asc) {
        asc = ( typeof(asc) !== 'undefined' ) ? asc : true; // Ascending (default) or descending aspect
        var my = {},            // store private member variables and functions
            pub = w.Aspect();   // store public member variables and functions
            
        pub.name = asc ? 'trigoni asc' : 'trigoni desc';
        pub.angle = asc ? 120 : 240;
        pub.cls = ' major green';
        
        pub.drawSign = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            pub.sign_left = pub.x0 + .85 * (pub.end_x - pub.x0) - pub.can.width / 2 - line + 'px';
            pub.sign_top = pub.y0 + .85 * (pub.end_y - pub.y0) - pub.can.height / 2 + 'px';
            
            pub.ctx.beginPath();
            pub.ctx.moveTo(pub.can.width / 2, line / 2);
            pub.ctx.lineTo(pub.can.width / 2 * (1 + Math.cos(Math.PI / 6)) - line / 2 * Math.cos(Math.PI / 6),
                           pub.can.height / 2 * (1 + Math.sin(Math.PI / 6)) - line / 2 * Math.sin(Math.PI / 6));
            pub.ctx.lineTo(pub.can.width / 2 * (1 - Math.cos(Math.PI / 6)) + line / 2 * Math.cos(Math.PI / 6),
                           pub.can.height / 2 * (1 + Math.sin(Math.PI / 6)) - line / 2 * Math.sin(Math.PI / 6));
            pub.ctx.closePath();
            pub.ctx.stroke();
            
            return pub.can;
        };
        
        return pub;
    };
    
    w.Semisextile = function(asc) {
        asc = ( typeof(asc) !== 'undefined' ) ? asc : true; // Ascending (default) or descending aspect
        var my = {},            // store private member variables and functions
            pub = w.Aspect();   // store public member variables and functions
            
        pub.name = asc ? 'semisextile asc' : 'semisextile desc';
        pub.angle = asc ? 30 : 330;
        pub.cls = ' minor green';
        
        pub.drawSign = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            
            pub.sign_left = pub.x0 + w._default.center_ratio * w._default.radius * (1 - Math.cos(pub.angle * Math.PI / 180)) / 2 - pub.can.width / 2 - line + 'px';
            pub.sign_top = asc
                            ? pub.y0 + w._default.center_ratio * w._default.radius * (1 - Math.sin(pub.angle * Math.PI / 180)) / 2 - pub.can.height / 2 + 'px'
                            : pub.y0 - w._default.center_ratio * w._default.radius * (1 - Math.abs(Math.sin(pub.angle * Math.PI / 180))) / 2 - pub.can.height / 2 + 'px';
            
            pub.ctx.beginPath();
            pub.ctx.translate(0, line);
            pub.ctx.moveTo(0, pub.can.height / 2);
            pub.ctx.lineTo(pub.can.width, pub.can.height / 2);
            pub.ctx.moveTo(pub.can.width / 2 * (1 - Math.cos(Math.PI / 3)), pub.can.height / 2 * (1 - Math.sin(Math.PI / 3)));
            pub.ctx.lineTo(pub.can.width / 2, pub.can.height / 2);
            pub.ctx.lineTo(pub.can.width / 2 * (1 + Math.cos(Math.PI / 3)), pub.can.height / 2 * (1 - Math.sin(Math.PI / 3)));
            pub.ctx.stroke();
            
            return pub.can;
        };
        
        return pub;
    };
    
    w.Angle = function(asc) {
        asc = ( typeof(asc) !== 'undefined' ) ? asc : true; // Ascending (default) or descending aspect
        var my = {},            // store private member variables and functions
            pub = w.Aspect();   // store public member variables and functions
            
        pub.name = asc ? 'angle asc' : 'angle desc';
        pub.angle = asc ? 45 : 315;
        pub.cls = ' minor red';
        
        pub.drawSign = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            
            pub.sign_left = pub.x0 + w._default.center_ratio * w._default.radius * (1 - Math.cos(pub.angle * Math.PI / 180)) * .7 - pub.can.width / 2 - line + 'px';
            pub.sign_top = asc
                            ? pub.y0 + w._default.center_ratio * w._default.radius * Math.sin(Math.PI / 4) * .7 - pub.can.height / 2 + 'px'
                            : pub.y0 - w._default.center_ratio * w._default.radius * Math.sin(Math.PI / 4) * .7 - pub.can.height / 2 + 'px';
            
            pub.ctx.beginPath();
            pub.ctx.moveTo(pub.can.width / 2, line / 2);
            pub.ctx.lineTo(pub.can.width / 2 * (1 - Math.cos(Math.PI / 6)) + line / 2 * Math.cos(Math.PI / 6),
                           pub.can.height / 2 * (1 + Math.sin(Math.PI / 6)) - line / 2 * Math.sin(Math.PI / 6));
            pub.ctx.lineTo(pub.can.width / 2 * (1 + Math.cos(Math.PI / 6)) - line / 2 * Math.cos(Math.PI / 6),
                           pub.can.height / 2 * (1 + Math.sin(Math.PI / 6)) - line / 2 * Math.sin(Math.PI / 6));
            pub.ctx.stroke();
            
            return pub.can;
        };
        
        return pub;
    };
    
    w.One_and_half_q = function(asc) {
        asc = ( typeof(asc) !== 'undefined' ) ? asc : true; // Ascending (default) or descending aspect
        var my = {q: .5},       // store private member variables and functions
            pub = w.Aspect();   // store public member variables and functions
            
        pub.name = asc ? 'one_and_half_q asc' : 'one_and_half_q desc';
        pub.angle = asc ? 135 : 225;
        pub.cls = ' minor red';
        
        pub.drawSign = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            h = (pub.can.width - line) * Math.cos(Math.PI / 4);
            pub.sign_left = pub.x0 + .85 * (pub.end_x - pub.x0) - line + 'px';
            pub.sign_top = pub.y0 + .85 * (pub.end_y - pub.y0) - pub.can.height / 2;
            if( !asc ) pub.sign_top -= 2 * line;
            pub.sign_top += 'px';
            
            pub.ctx.beginPath();
            
            pub.ctx.rect((pub.can.width - h) / 2,(pub.can.height - h) / 2,pub.can.width * my.q,pub.can.width * my.q);
            pub.ctx.moveTo((pub.can.width - h) / 2 + pub.can.width * my.q / 2,
                           (pub.can.height - h) / 2 + pub.can.height * my.q / 2);
            pub.ctx.lineTo((pub.can.width - h) / 2 + pub.can.width * my.q / 2,
                           (pub.can.height - h) / 2 + pub.can.height * my.q / 2 * 3);
            pub.ctx.lineTo((pub.can.width - h) / 2 + pub.can.width * my.q,
                           (pub.can.height - h) / 2 + pub.can.height * my.q / 2 * 3);
            pub.ctx.stroke();
            
            return pub.can;
        };
        
        return pub;
    };
    
    w.Quincunx = function(asc) {
        asc = ( typeof(asc) !== 'undefined' ) ? asc : true; // Ascending (default) or descending aspect
        var my = {},            // store private member variables and functions
            pub = w.Aspect();   // store public member variables and functions
            
        pub.name = asc ? 'quincunx asc' : 'quincunx desc';
        pub.angle = asc ? 150 : 210;
        pub.cls = ' minor green';
        
        pub.drawSign = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            
            pub.sign_left = pub.x0 + .85 * (pub.end_x - pub.x0) - pub.can.width / 2 - line + 'px';
            pub.sign_top = pub.y0 + .85 * (pub.end_y - pub.y0) - pub.can.height / 2 + 'px';
            
            pub.ctx.beginPath();
            pub.ctx.translate(0, -line);
            pub.ctx.moveTo(0, pub.can.height / 2);
            pub.ctx.lineTo(pub.can.width, pub.can.height / 2);
            pub.ctx.moveTo(pub.can.width / 2 * (1 - Math.cos(Math.PI / 3)), pub.can.height / 2 * (1 + Math.sin(Math.PI / 3)));
            pub.ctx.lineTo(pub.can.width / 2, pub.can.height / 2);
            pub.ctx.lineTo(pub.can.width / 2 * (1 + Math.cos(Math.PI / 3)), pub.can.height / 2 * (1 + Math.sin(Math.PI / 3)));
            pub.ctx.stroke();
            
            return pub.can;
        };
        
        return pub;
    };
    
    w.Planet = function() {
        var my = {},            // store private member variables and functions
            pub = w.Canvas();	// store public member variables and functions
        
        // Default canvas height
        my.defaultHeight = w._default.sign_size;
        
        my.planets = ['sun', 'moon', 'mercury', 'venus', 'mars', 'jupiter', 'saturn', 'uran', 'neptune', 'pluto'];
        
        // Отношение ширины к высоте по умолчанию. Если задать отрицательное значение,
        // то ширина будет равна заданному значению высоты, а высота пересчитана
        // с абсолютным значением этого коэфициента.
        pub.ratio = 1;          
        
        pub.get_all = function() {
            return my.planets;
        };
        
        pub.by_number = function(i) {
            return w[my.planets[i].capitalize()]();
        };
        
        // Prepare template canvas for further processing.
        pub.makeCanvas = function (h, c, line) {
            h = ( typeof(h) !== 'undefined' ) ? h : my.defaultHeight;
            c = pub.getColor(c);
            line = ( typeof(line) !== 'undefined' ) ? line : h / 10;

            var w = (pub.ratio > 0) ? pub.ratio * h : h;
            var h = (pub.ratio > 0) ? h : Math.abs(pub.ratio) * h;

            pub._makeCanvas(h, w, c, line);
        };
        
        pub.draw = function(h, color, line) {
            var sign = pub.drawPlanet(h, color, line);
            sign.setAttribute("title", i18n[pub.name]);
            return sign;
        };
        
        return pub;
    };
    
    w.Sun = function() {
        var my = {x_comp: 0.75, y_comp: 0.9},
            pub = w.Planet();
        
        pub.name = 'Sun';
        pub.order = 0;
        
        pub.drawPlanet = function(h, color, line) {
            
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            
            var x_comp = my.x_comp / (my.x_comp + Math.sqrt(line / (pub.can.width * 0.1)) * (1 - my.x_comp)),
                y_comp = my.y_comp / (my.y_comp + Math.sqrt(line / (pub.can.width * 0.1)) * (1 - my.y_comp));
        
            pub.ctx.save();
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        pub.can.height / 2,
                        pub.can.height / 2,
                        0,
                        2*Math.PI);
            pub.ctx.fill();


            // set global composite to make visible field between pathes
            pub.ctx.globalCompositeOperation = 'destination-out';

            // Make same circle compressed to oval (keep in mind position is compressed too).
            pub.ctx.scale(x_comp, y_comp);
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2 / x_comp,
                        pub.can.height / 2 / y_comp,
                        pub.can.height / 2,
                        0,
                        2*Math.PI);
            pub.ctx.fill();
            pub.ctx.restore();

            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        pub.can.height / 2,
                        pub.can.height / 10,
                        0,
                        2*Math.PI);
            pub.ctx.fill();

            return pub.can;
        };

        return pub;
    };
    
    w.Moon = function() {
        var my = {comp: 0.7, start: -Math.PI + 30 * Math.PI / 180, end: -Math.PI - 30 * Math.PI / 180},
            pub = w.Planet();
        
        pub.name = 'Moon';
        pub.order = 1;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            
            var comp = my.comp / (my.comp + Math.sqrt(line / (pub.can.width * 0.1)) * (1 - my.comp));
            
            pub.ctx.save();
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        pub.can.height / 2,
                        pub.can.height / 2,
                        my.start,
                        my.end);
            pub.ctx.fill();


            // set global composite to make visible field between pathes
            pub.ctx.globalCompositeOperation = 'destination-out';

            // Make same circle compressed (keep in mind position is compressed too).
            pub.ctx.scale(comp, comp);
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        pub.can.height / 2 / comp,
                        pub.can.height / 2,
                        0,
                        2*Math.PI);
            pub.ctx.fill();
            pub.ctx.restore();

            return pub.can;
        };

        return pub;
    };
    
    w.Mercury = function() {
        var my = {comp: 0.8, outer_r: 0.8, inner_r: 0.5},
            pub = w.Planet();
        
        pub.name = 'Mercury';
        pub.order = 2;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            
            line = pub.ctx.lineWidth;
            
            var inner_r = my.inner_r / (my.inner_r + Math.sqrt(line / (pub.can.width * 0.1)) * (1 - my.inner_r));
            pub.ctx.save();

//---------- Lag   
            pub.ctx.globalCompositeOperation = 'source-over';
            pub.ctx.beginPath();
            pub.ctx.moveTo(pub.can.width / 2, pub.can.height);
            pub.ctx.lineTo( pub.can.width / 2, 
                            pub.can.height * my.outer_r * my.comp);
            pub.ctx.moveTo(pub.can.width / 3, pub.can.height * 3 / 4 + pub.ctx.lineWidth / 2);
            pub.ctx.lineTo( pub.can.width * 2 / 3, 
                            pub.can.height * 3 / 4 + pub.ctx.lineWidth / 2);
            pub.ctx.stroke();
//---------- Horns
            // Upper outer oval
            pub.ctx.setTransform(1, 0, 0, my.comp, 0, 0);
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        0,
                        my.outer_r * pub.can.height / 2,
                        -30 * Math.PI / 180,
                        -Math.PI + 30 * Math.PI / 180);
            pub.ctx.fill();
            
            
            // set global composite to make visible field between pathes
            pub.ctx.globalCompositeOperation = 'destination-out';
            pub.ctx.setTransform(1, 0, 0, inner_r * my.comp, pub.can.width * (1 - my.comp) / 2, 0);
//            pub.ctx.scale(1 / my.comp, inner_r * my.comp);
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2 * my.comp,
                        0,
                        my.outer_r * pub.can.height / 2,
                        -30 * Math.PI / 180,
                        -Math.PI + 30 * Math.PI / 180);
            pub.ctx.fill();
            pub.ctx.restore();
            
//---------- Head            
            // Outer oval
            pub.ctx.globalCompositeOperation = 'destination-over';
            pub.ctx.setTransform(1, 0, 0, my.comp, 0, 0);
//            pub.ctx.scale(1, my.comp);
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        pub.can.height / 2,
                        my.outer_r * pub.can.height / 2,
                        0,
                        2 * Math.PI);
            pub.ctx.fill();
            
            // Inner circle
            pub.ctx.globalCompositeOperation = 'destination-out';
            pub.ctx.setTransform(1, 0, 0, 1, 0, 0);
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        pub.can.height / 2 * my.comp,
                        inner_r * pub.can.height / 2,
                        0,
                        2 * Math.PI);
            pub.ctx.fill();
            


            return pub.can;
        };

        return pub;
    };
    
    w.Venus = function() {
        var my = {comp: 0.8, outer_r: 0.8, inner_r: 0.5},
            pub = w.Planet();
        
        pub.name = 'Venus';
        pub.order = 3;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            
            line = pub.ctx.lineWidth;            
            var inner_r = my.inner_r / (my.inner_r + Math.sqrt(line / (pub.can.width * 0.1)) * (1 - my.inner_r));
            
            pub.ctx.save();

//---------- Lag   
            pub.ctx.globalCompositeOperation = 'source-over';
            pub.ctx.beginPath();
            pub.ctx.moveTo(pub.can.width / 2, pub.can.height);
            pub.ctx.lineTo( pub.can.width / 2, 
                            pub.can.height * my.outer_r * my.comp + pub.ctx.lineWidth / 2);
            pub.ctx.moveTo(pub.can.width / 3, pub.can.height * 3 / 4 + pub.ctx.lineWidth / 2);
            pub.ctx.lineTo( pub.can.width * 2 / 3, 
                            pub.can.height * 3 / 4 + pub.ctx.lineWidth / 2);
            pub.ctx.stroke();
            
//---------- Head            
            // Outer oval
            pub.ctx.globalCompositeOperation = 'destination-over';
            pub.ctx.setTransform(1, 0, 0, my.comp, 0, 0);
//            pub.ctx.scale(1, my.comp);
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        pub.can.height / 2,
                        my.outer_r * pub.can.height / 2,
                        0,
                        2 * Math.PI);
            pub.ctx.fill();
            
            // Inner circle
            pub.ctx.globalCompositeOperation = 'destination-out';
            pub.ctx.setTransform(1, 0, 0, 1, 0, 0);
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        pub.can.height / 2 * my.comp,
                        inner_r * pub.can.height / 2,
                        0,
                        2 * Math.PI);
            pub.ctx.fill();

            return pub.can;
        };

        return pub;
    };
    
    w.Mars = function() {
        var my = {scale: 0.8, skew: -0.16},
            pub = w.Planet();
        
        pub.name = 'Mars';
        pub.order = 4;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            line = pub.ctx.lineWidth;
            var inner_k = 1 / (my.scale + Math.sqrt(line / (pub.can.width * 0.1)) * (1 - my.scale));
            
            pub.ctx.save();
            
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2 * my.scale,
                        pub.can.height * (1 - 1 / 2 * my.scale),
                        pub.can.height / 2 * my.scale,
                        0,
                        2*Math.PI);
            pub.ctx.fill();
            
            pub.ctx.beginPath();
            
            pub.ctx.moveTo(pub.can.width / 2,
                           pub.can.height / 2);
            pub.ctx.lineTo(pub.can.width - 0.2 * pub.can.width / inner_k, 0.2 * pub.can.width / inner_k);
            pub.ctx.stroke();
            
            pub.ctx.beginPath();
            pub.ctx.moveTo(pub.can.width - pub.ctx.lineWidth,
                           pub.ctx.lineWidth);
            pub.ctx.lineTo(pub.can.width - 0.3 * pub.can.width / inner_k, 0.2 * pub.can.width / inner_k);
            pub.ctx.lineTo(pub.can.width - 0.2 * pub.can.width / inner_k,
                           0.3 * pub.can.width / inner_k);
                           
            pub.ctx.closePath();
            
            pub.ctx.lineJoin = 'miter';
            pub.ctx.miterLimit = 0.2 * pub.can.width;
            pub.ctx.stroke();
            pub.ctx.fill();

            // set global composite to make visible field between pathes
            pub.ctx.globalCompositeOperation = 'destination-out';
            
            // Make same circle compressed to oval (keep in mind position is compressed too).
            pub.ctx.setTransform(my.scale, my.skew, 0, my.scale, pub.can.width * my.scale / 10, pub.can.height / 10 * (1 + my.scale));
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2 * my.scale,
                        pub.can.height * (1 - 1 / 2 * my.scale),
                        pub.can.height / 2 * my.scale * inner_k,
                        0,
                        2*Math.PI);
            pub.ctx.fill();
            
            return pub.can;
        };

        return pub;
    };
    
    w.Jupiter = function() {
        var my = {vert_x: 0.65, line: 0.1},
            pub = w.Planet();
        
        pub.name = 'Jupiter';
        pub.order = 5;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            
            var lw = my.line * pub.can.width;
            line = pub.ctx.lineWidth;
            var d = line - lw, vert_x = my.vert_x * pub.can.width;
            
            pub.ctx.beginPath();
            pub.ctx.moveTo(vert_x, pub.can.height);
            pub.ctx.lineTo(vert_x, vert_x + lw + d / 2);
            pub.ctx.lineTo(lw - d / 2, vert_x + lw + d / 2);
            pub.ctx.bezierCurveTo(  vert_x - lw * 2 - d / 2, 2.5 * lw + d / 2,
                                    lw * 3.5 - d / 2, lw + d / 2,
                                    lw - d / 2, 1.5 * lw );
            pub.ctx.bezierCurveTo(  lw * 3.5, -2 * lw,
                                    lw * 6 + d / 2, lw,
                                    lw * 3 + d / 2, vert_x - d / 2 );
            pub.ctx.lineTo(vert_x, vert_x - d / 2);
            pub.ctx.lineTo(vert_x, 1.5 * lw);
            pub.ctx.lineTo(vert_x + line, 1.5 * lw);
            pub.ctx.lineTo(vert_x + line, vert_x - d / 2);
            pub.ctx.lineTo(pub.can.width - lw + d, vert_x - d / 2);
            pub.ctx.lineTo(pub.can.width - lw + d, vert_x + lw + d / 2);
            pub.ctx.lineTo(vert_x + line, vert_x + lw + d / 2);
            pub.ctx.lineTo(vert_x + line, pub.can.height);
            pub.ctx.closePath();
            
            pub.ctx.fill();

            return pub.can;
        };

        return pub;
    };
    
    w.Saturn = function() {
        var my = {line: 0.1},
            pub = w.Planet();
        
        pub.name = 'Saturn';
        pub.order = 6;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            var lw = my.line * pub.can.width;
            line = pub.ctx.lineWidth;
            var d = line - lw;
            
            pub.ctx.beginPath();
            
            pub.ctx.moveTo(lw * 3 - d / 2, 0);
            pub.ctx.lineTo(lw * 3 - d / 2, 0.8 * pub.can.height);
            pub.ctx.moveTo(lw - d, 0.2 * pub.can.height);
            pub.ctx.lineTo(lw * 3 + lw * 2, 0.2 * pub.can.height);
            pub.ctx.stroke();
            
            pub.ctx.beginPath();
            pub.ctx.moveTo(lw * 3 - d / 2 + line / 2, pub.can.height / 2 - line / 2);
            pub.ctx.bezierCurveTo(  lw * 7 - d / 2, 1.5 * lw - d / 2,
                                    lw * 8 + d / 2, lw * 3,
                                    lw * 8 + d / 2, lw * 5 );
            pub.ctx.bezierCurveTo(  lw * 8 + d / 2, lw * 6.5,
                                    lw * 6 + d / 2, pub.can.height,
                                    lw * 9 + d / 2, lw * 8 );
            pub.ctx.bezierCurveTo(  lw * 8.5 + d / 2, pub.can.height,
                                    pub.can.width / 2 - d / 2, pub.can.height * 1.2,
                                    lw * 6.5 - d / 2, lw * 6 );
            pub.ctx.bezierCurveTo(  lw * 7 - d / 2, lw * 4,
                                    lw * 6 - d / 2, lw * 3.5 + d / 2,
                                    lw * 3 - d / 2 + line / 2, pub.can.height / 2 + line / 2 );
            
            pub.ctx.closePath();
            
            pub.ctx.fill();

            return pub.can;
        };
        
        

        return pub;
        
        
    };
    
    w.Uran = function() {
        var my = {line: 0.1,    // default line width
                  dy: 0.31,     // ear circle center vertical position
                  dx: 0.29,     // Bigger ear circle (inner line) default horizontal padding
                  R: 1,         // Bigger ear circle diameter reletive to canvas height
                  r: 0.65,      // Smaller ear circle diameter reletive to canvas height
                  LR: 0.2,      // Outer lower circle radius reletive to canvas height
                  scale: 0.9},  // Lower outer circle vertical compression
            pub = w.Planet();
        
        pub.name = 'Uran';
        pub.order = 7;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            var lw = my.line * pub.can.width;
            line = pub.ctx.lineWidth;
            var d = line - lw;
            
            pub.ctx.save();

//          Left ear            
            pub.ctx.beginPath();
            
            pub.ctx.arc(0,
                        my.dy * pub.can.height,
                        my.r * pub.can.height / 2,
                        0,
                        2*Math.PI);
            pub.ctx.fill();
            
            pub.ctx.globalCompositeOperation = 'destination-out';
            pub.ctx.beginPath();
            pub.ctx.arc(-my.dx * pub.can.width - 3 * d, // 2d + 1/6 d
                        my.dy * pub.can.height,
                        my.R * pub.can.height / 2 + 2 * d,
                        0,
                        2*Math.PI);
            pub.ctx.fill();
            
//          Right ear           
            pub.ctx.globalCompositeOperation = 'destination-over';
            pub.ctx.beginPath();
            
            pub.ctx.arc(pub.can.width,
                        my.dy * pub.can.height,
                        my.r * pub.can.height / 2,
                        0,
                        2*Math.PI);
            pub.ctx.fill();
            
            pub.ctx.globalCompositeOperation = 'destination-out';
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width + my.dx * pub.can.width + 3 * d, // 2d + 1/6 d
                        my.dy * pub.can.height,
                        my.R * pub.can.height / 2 + 2 * d, // 4d + 1/6 d
                        0,
                        2*Math.PI);
            pub.ctx.fill();
            
//          Cross
            pub.ctx.globalCompositeOperation = 'destination-over';
            pub.ctx.beginPath();
            pub.ctx.moveTo(pub.can.width / 2, lw);
            pub.ctx.lineTo(pub.can.width / 2, pub.can.height - lw);
            pub.ctx.moveTo(pub.can.width * 0.3, my.dy * pub.can.height);
            pub.ctx.lineTo(pub.can.width * 0.7, my.dy * pub.can.height);
            pub.ctx.stroke();

//          Lower circle
            pub.ctx.beginPath();
            pub.ctx.setTransform(1, 0, 0, my.scale, 0, 0);
            pub.ctx.arc(pub.can.width / 2,
                        pub.can.height * (1 - my.LR) / my.scale,
                        my.LR * pub.can.height,
                        0,
                        2*Math.PI);
            pub.ctx.fill();
            pub.ctx.restore();
            
            pub.ctx.globalCompositeOperation = 'destination-out';
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        pub.can.height * (1 - my.LR),
                        lw - d,
                        0,
                        2*Math.PI);
            pub.ctx.fill();
            
            
            return pub.can;
        };
        
        

        return pub;
        
        
    };
    
    w.Neptune = function() {
        var my = {line: 0.1},
            pub = w.Planet();
        
        pub.name = 'Neptune';
        pub.order = 8;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            var lw = my.line * pub.can.width;
            line = pub.ctx.lineWidth;
            var d = line - lw;
            
            pub.ctx.beginPath();
            
            pub.ctx.moveTo(pub.can.width / 2 - line / 2, pub.can.height);
            pub.ctx.lineTo(pub.can.width / 2 - line / 2, 0.7 * pub.can.height + line / 2);
            pub.ctx.lineTo(0, lw * 7 + line / 2);

            pub.ctx.bezierCurveTo(  lw, lw * 4,
                                    lw - d / 2, lw * 2,
                                    0, 0 );
            pub.ctx.bezierCurveTo(  lw * 2 + d / 2, lw,
                                    lw * 2 + d / 2, pub.can.height / 2,
                                    lw * 1.5 + d / 2, lw * 7 - line / 2 );
                                    
            pub.ctx.lineTo(pub.can.width / 2 - line / 2, lw * 7 - line / 2);
            pub.ctx.lineTo(pub.can.width / 2 - line / 2, line * 1.5);
            pub.ctx.lineTo(pub.can.width / 2 - line * 1.5, line * 2);
            pub.ctx.lineTo(pub.can.width / 2, 0);
            pub.ctx.lineTo(pub.can.width / 2 + line * 1.5, line * 2);
            pub.ctx.lineTo(pub.can.width / 2 + line / 2, line * 1.5);
            pub.ctx.lineTo(pub.can.width / 2 + line / 2, lw * 7 - line / 2);
            pub.ctx.lineTo(pub.can.width - lw * 1.5 - d / 2, lw * 7 - line / 2);
            
            pub.ctx.bezierCurveTo(  pub.can.width - lw * 2 - d / 2, pub.can.height / 2,
                                    pub.can.width - lw * 2 - d / 2, lw,
                                    pub.can.width, 0 );
                                    
            pub.ctx.bezierCurveTo(  pub.can.width - lw + d/ 2, lw * 2,
                                    pub.can.width - lw + d / 2, lw * 4,
                                    pub.can.width, lw * 7 + line / 2 );
                                    
            pub.ctx.lineTo(pub.can.width / 2 + line / 2, lw * 7 + line / 2);
            pub.ctx.lineTo(pub.can.width / 2 + line / 2, pub.can.height);
            
            pub.ctx.closePath();
            
            pub.ctx.fill();

            return pub.can;
        };
        
        

        return pub;
        
        
    };
    
    w.Pluto = function() {
        var my = {line: 0.1,
                  LR: 0.2,      // Head outer circle radius reletive to canvas height
                  scale: 0.9},  // Head outer circle vertical compression
            pub = w.Planet();
        
        pub.name = 'Pluto';
        pub.order = 9;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            var lw = my.line * pub.can.width;
            line = pub.ctx.lineWidth;
            var d = line - lw;
            
            pub.ctx.save();
            pub.ctx.beginPath();
            
            pub.ctx.moveTo(pub.can.width / 2, 0);
            pub.ctx.lineTo( pub.can.width / 2, pub.can.height);
            pub.ctx.moveTo(pub.can.width / 3, pub.can.height * 3 / 4 + line / 2);
            pub.ctx.lineTo( pub.can.width * 2 / 3, pub.can.height * 3 / 4 + line / 2);
            pub.ctx.stroke();

            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        lw,
                        pub.can.width * 0.45 + d,
                        - Math.PI / 18,
                        Math.PI * (1 + 1 / 18) );
            pub.ctx.fill();
            
            pub.ctx.globalCompositeOperation = 'destination-out';
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        lw,
                        pub.can.width * 0.35,
                        0,
                        2 * Math.PI);
            pub.ctx.fill();
            
            pub.ctx.globalCompositeOperation = 'destination-over';
            pub.ctx.setTransform(1, 0, 0, my.scale, 0, 0);
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        my.LR * pub.can.width,
                        my.LR * pub.can.width,
                        0,
                        2 * Math.PI );
            pub.ctx.fill();
            pub.ctx.restore();
            
            pub.ctx.globalCompositeOperation = 'destination-out';
            pub.ctx.beginPath();
            pub.ctx.arc(pub.can.width / 2,
                        my.LR * pub.can.width * my.scale,
                        lw - d,
                        0,
                        2 * Math.PI );
            
            pub.ctx.fill();

            return pub.can;
        };
        
        

        return pub;
        
        
    };
    
    w.Luna = function(is_white) {
        var my = {line: 0.1,
                  y_center: 0.34,
                  is_white: (typeof is_white !== 'undefined' ? is_white : false)},
            pub = w.Planet();
        
        pub.name = my.is_white ? 'Selena' : 'Lilit';
        pub.order = my.is_white ? 10 : 11;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            var lw = my.line * pub.can.width;
            line = pub.ctx.lineWidth;
            var d = line - lw;
            
//            pub.ctx.save();
            pub.ctx.beginPath();
            
            pub.ctx.moveTo(pub.can.width / 2, 2 * my.y_center * pub.can.height - d / 4);
            pub.ctx.lineTo(pub.can.width / 2, pub.can.height);
            pub.ctx.moveTo(pub.can.width / 3, pub.can.height * 3 / 4 + line / 2);
            pub.ctx.lineTo( pub.can.width * 2 / 3, pub.can.height * 3 / 4 + line / 2);
            pub.ctx.stroke();
            
            if ( !my.is_white ) {
                pub.ctx.translate(pub.can.width / 2, pub.can.height * my.y_center );
                pub.ctx.rotate(Math.PI);
                pub.ctx.translate(-pub.can.width / 2, -pub.can.height * my.y_center - 0.3 * lw );
            }

            pub.ctx.beginPath();
            pub.ctx.moveTo(pub.can.width / 5, pub.can.height / 5);
            pub.ctx.bezierCurveTo(  lw * 2.5, lw,
                                    lw * 3.7, line / 4,
                                    pub.can.width / 2, line / 4 );
            pub.ctx.bezierCurveTo(  lw * 7, line / 4,
                                    lw * 8.5, lw * 1.6,
                                    lw * 8.5, my.y_center * pub.can.height );
            pub.ctx.bezierCurveTo(  lw * 8.5, lw * 5.2,
                                    lw * 7, 2 * my.y_center * pub.can.height - d / 4,
                                    pub.can.width / 2, 2 * my.y_center * pub.can.height - d / 4 );
            pub.ctx.bezierCurveTo(  lw * 3.7, 2 * my.y_center * pub.can.height - d / 4,
                                    lw * 2.5, lw * 5.8,
                                    pub.can.width / 5, lw * 4.8 );
                                    
            pub.ctx.bezierCurveTo(  lw * 2.5, lw * 5.4,
                                    lw * 3.1, lw * 6 - d / 4,
                                    lw * 4 - d / 4, lw * 6 - d / 4 );
            pub.ctx.bezierCurveTo(  lw * 5.4 - d / 4, lw * 6 - d / 4,
                                    lw * 6.5 - d / 2, lw * 4.8,
                                    lw * 6.5 - d / 2, my.y_center * pub.can.height );
            pub.ctx.bezierCurveTo(  lw * 6.5 - d / 2, lw * 2,
                                    lw * 5.4 - d / 4, lw * 0.8 - d / 4,
                                    lw * 4 - d / 4, lw * 0.8 - d / 4 );
            pub.ctx.bezierCurveTo(  lw * 3.1, lw * 0.8 - d / 4,
                                    lw * 2.5, lw * 1.4,
                                    pub.can.width / 5, pub.can.height / 5 );
            pub.ctx.closePath();
            pub.ctx.lineWidth = line / 2;
            pub.ctx.lineJoin = 'miter';
            pub.ctx.miterLimit = lw / 2;
            pub.ctx.stroke();
            
            if ( !my.is_white ) {
                pub.ctx.fill();
            }

            return pub.can;
        };
        
        

        return pub;
        
        
    };
    
    w.LunarNode = function(is_south) {
        var my = {line: 0.1,
                  x_point: 0.3122,
                  y_point: 0.815,
                  x_circle: 0.2,
                  y_circle: 0.86,
                  R: 0.1256,
                  scale: 0.7795,    // Circles vertical compression
                  is_south: (typeof is_south !== 'undefined' ? is_south : false)},
            pub = w.Planet();
        
        pub.name = my.is_south ? 'DescLN' : 'AscLN';
        pub.order = my.is_south ? 13 : 12;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            var lw = my.line * pub.can.width;
            line = pub.ctx.lineWidth;
            var d = line - lw;
            
            if ( my.is_south ) {
                pub.ctx.translate(pub.can.width / 2, pub.can.height / 2 );
                pub.ctx.rotate(Math.PI);
                pub.ctx.translate(-pub.can.width / 2, -pub.can.height / 2 );
                my.y_circle = (pub.can.height * (1 - my.y_circle) + d / 2) / my.scale;
            }
            else {
                my.y_circle = (pub.can.height * my.y_circle - d / 2) / my.scale;
            }
            
            pub.ctx.beginPath();
            
            pub.ctx.moveTo(pub.can.width * my.x_point, pub.can.width * my.y_point - d / 2);
            pub.ctx.bezierCurveTo(  -lw * 6, -2 * lw + d / 2,
                                    pub.can.width + lw * 6, -2 * lw + d / 2,
                                    pub.can.width * (1 - my.x_point), pub.can.width * my.y_point - d / 2 );
            pub.ctx.stroke();

            pub.ctx.setTransform(1, 0, 0, my.scale, 0, 0);
            pub.ctx.beginPath();
            pub.ctx.arc(            pub.can.width * my.x_circle,
                                    my.y_circle,
                                    pub.can.width * my.R,
                                    0,
                                    2 * Math.PI );
            pub.ctx.stroke();
            
            pub.ctx.beginPath();
            pub.ctx.arc(            pub.can.width * (1 - my.x_circle),
                                    my.y_circle,
                                    pub.can.width * my.R,
                                    0,
                                    2 * Math.PI );
            pub.ctx.stroke();
            

            return pub.can;
        };
        
        

        return pub;
        
        
    };
    
    w.ParsFortunae = function() {
        var my = {line: 0.1},
            pub = w.Planet();
        
        pub.name = 'ParsF';
        pub.order = 14;
        
        pub.drawPlanet = function(h, color, line) {
            pub.makeCanvas(h, color, line);
            var lw = my.line * pub.can.width;
            line = pub.ctx.lineWidth;
            var d = line - lw;
            
            pub.ctx.translate(pub.can.width / 2, pub.can.height / 2 );
            pub.ctx.rotate(Math.PI / 4);
            pub.ctx.translate(-pub.can.width / 2, -pub.can.height / 2 );
            
            pub.ctx.beginPath();
            
            pub.ctx.moveTo(pub.can.width / 2, 0);
            pub.ctx.lineTo(pub.can.width / 2, pub.can.height);
            pub.ctx.moveTo(0, pub.can.height / 2);
            pub.ctx.lineTo(pub.can.width, pub.can.height / 2);
            pub.ctx.stroke();
            
            pub.ctx.beginPath();
            pub.ctx.arc(            pub.can.width / 2,
                                    pub.can.height / 2,
                                    (pub.can.width - line) / 2,
                                    0,
                                    2 * Math.PI );
            pub.ctx.stroke();            

            return pub.can;
        };
        
        return pub;
    };
        
}(window));