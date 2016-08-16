(function(w){
    
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