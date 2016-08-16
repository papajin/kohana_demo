(function(w){
    
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
}(window));