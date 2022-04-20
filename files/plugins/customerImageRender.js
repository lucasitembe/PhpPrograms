//A jqplot plugin to render image as a data point.
(function($) {
	$.jqplot.ImageMarkerRenderer = function() {
		$.jqplot.MarkerRenderer.call(this);
		//image element which should have src attribute populated with the image source path
		this.imageElement = null;
		//the offset to be added to the x position of the point to align the image correctly in the center of the point.
		this.xOffset = null;
		//the offset to be added to the y position of the point to align the image correctly in the center of the point.
		this.yOffset = null;
	};
	$.jqplot.ImageMarkerRenderer.prototype = new $.jqplot.MarkerRenderer();
	$.jqplot.ImageMarkerRenderer.constructor = $.jqplot.ImageMarkerRenderer;

	$.jqplot.ImageMarkerRenderer.prototype.init = function(options) {
		options = options || {};
		this.imageElement = options.imageElement;
		this.xOffset = options.xOffset || 0;
		this.yOffset = options.yOffset || 0;
		$.jqplot.MarkerRenderer.prototype.init.call(this, options);
	};

	$.jqplot.ImageMarkerRenderer.prototype.draw = function(x, y, ctx, options) {
		//draw the image onto the canvas
		ctx.drawImage(this.imageElement, x + this.xOffset, y + this.yOffset);
		ctx.restore();
		return;
	};
})(jQuery);