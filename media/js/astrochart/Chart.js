(function(w){
    w._default = {
                radius: 358,
                inner_ratio: 0.8883,
                center_ratio: 0.5587,
                line: 2,
                sign_size: 25,
                highlight_color: '#E5F3FB',
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
            var id = $btn.attr('id').replace('__', '');
            var parts = id.split(' '); 
            var inst  = parts.length > 1 
                                    ? w[parts[0].capitalize()](parts[1] === 'asc')
                                    : w[parts[0].capitalize()]();
            
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
                             html: '<h5>'+i18n[pl]+'</h5>'} );
                         console.log(host[pl].length);
                if (host[pl].length) {
                    $.each(host[pl], function(){
                        $(w[this.toString()]().draw(20, col, 2)).addClass('pull-right').appendTo(row.find('h5'));
                    });
                }
                else {
                    $('<span/>',{class:'pull-right',css:{color:col,fontWeight:'bold'},html:'&mdash;'}).appendTo(row.find('h5'));
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
}(window));