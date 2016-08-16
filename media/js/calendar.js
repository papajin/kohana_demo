$('#calendar').parent().magnificPopup({
    type:'image',
    titleSrc: 'title',
    delegate: 'a',

    gallery: {
        enabled: true,
        preload: [ 1, 1],
        callbacks: {
            buildControls: function() {
                // re-appends controls inside the main container
                this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
            }
        }
    }
});