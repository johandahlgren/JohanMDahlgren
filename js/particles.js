// RequestAnimFrame: a browser API for getting smooth animations
window.requestAnimFrame = (function(){
  return  window.requestAnimationFrame       ||
		  window.webkitRequestAnimationFrame ||
		  window.mozRequestAnimationFrame    ||
		  window.oRequestAnimationFrame      ||
		  window.msRequestAnimationFrame     ||
		  function( callback ){
			window.setTimeout(callback, 1000 / fps);
		  };
})();


// Some variables for later use
var particleCount 		= 100,
	particles 			= [],
	minDist 			= 200,
	drawLines 			= false,
	interact 			= false,
	accelerate 			= true,
	randomOpacity		= true,
	sizeCycle			= false,
	useBitmap			= true,
	rotate				= true,
	particleImageSrc 	= "style/images/seed.png",
	fps 				= 20,
	speedDamper 		= 40,
	opacityDamper 		= 7,
	radiusDamper		= 1,
	minRadius			= 1,
	maxRadius			= 4,
	time 				= 0,
	particleWeight		= -0.5,
	fadeCycleTime		= 3,
	particleImageMin 	= 20,
	particleImageMax 	= 50,
	newOpacity,
	dist,
	W,
	H,
	ctx,
	backgroundImage,
	particleImage,
	ratio,
	rotationX, 
	rotationY,
	offsetX,
	offsetY;


$(window).load(function () {
	if (useCanvasBackground) {
		addImgAttributes();

		$("body").prepend("<canvas id='canvas'></canvas>");

		var imageCss = $("body").css("background-image");
		backgroundImage = new Image;
		imageCss = imageCss.substring(imageCss.indexOf("(") + 1, imageCss.indexOf(")"));
		backgroundImage.src = imageCss;//"http://www.dahlgren.tv/johan/boksiten/style/images/background.jpg";
		var backgroundWidth = backgroundImage.width;
		var backgroundHeight = backgroundImage.height;
		ratio = backgroundWidth / backgroundHeight;

		particleImage = new Image;
		particleImage.src = particleImageSrc;

		// Initializing the canvas
		// I am using native JS here, but you can use jQuery,
		// Mootools or anything you want
		var canvas = document.getElementById("canvas");

		// Initialize the context of the canvas
		ctx = canvas.getContext("2d");

		// Set the canvas width and height to occupy full window
		W = window.innerWidth, H = window.innerHeight;
		canvas.width = W;
		canvas.height = W / ratio; //H;

		// Time to push the particles into an array
		for(var i = 0; i < particleCount; i++) {
			particles.push(new Particle());
		}

		animloop();
	}
});


function addImgAttributes() {
    for( i=0; i < document.images.length; i++) {
        width = document.images[i].width;
        height = document.images[i].height;
        window.document.images[i].setAttribute("width",width);
        window.document.images[i].setAttribute("height",height);
    }
}

// Function to paint the canvas black
function paintCanvas() {
	// Set the fill color to black
	//ctx.fillStyle = "rgba(0,0,0,0.8)";

	ctx.globalAlpha = 1;
	ctx.drawImage(backgroundImage,0,0, W, W / ratio);

	// This will create a rectangle of white color from the
	// top left (0,0) to the bottom right corner (W,H)
	//ctx.fillRect(0,0,W,H);
}

// Now the idea is to create some particles that will attract
// each other when they come close. We will set a minimum
// distance for it and also draw a line when they come
// close to each other.

// The attraction can be done by increasing their velocity as
// they reach closer to each other

// Let's make a function that will act as a class for
// our particles.

function Particle() {
	// Position them randomly on the canvas
	// Math.random() generates a random value between 0
	// and 1 so we will need to multiply that with the
	// canvas width and height.
	this.x = Math.random() * W;
	this.y = Math.random() * H;
	
	this.radius = Math.round(Math.random() * (particleImageMax - particleImageMin) + particleImageMin);
	
	this.width = this.radius;
	this.height = this.width;
		
	// Now the radius of the particles. I want all of
	// them to be equal in size so no Math.random() here..
	this.originalRadius = this.radius;

	// We would also need some velocity for the particles
	// so that they can move freely across the space
	this.vx = (-1 + Math.random() * 2) * (this.radius / 3) / speedDamper;
	this.vy = (-1 + Math.random() * 2) * (this.radius / 3) / speedDamper;

	this.opacity = Math.random() * 1;
	this.originalOpacity = this.opacity;
	
	this.rotation = Math.random() * 360;
	this.rotationSpeed = Math.random() * 2 - 1;

	// This is the method that will draw the Particle on the
	// canvas. It is using the basic fillStyle, then we start
	// the path and after we use the `arc` function to
	// draw our circle. The `arc` function accepts four
	// parameters in which first two depicts the position
	// of the center point of our arc as x and y coordinates.
	// The third value is for radius, then start angle,
	// end angle and finally a boolean value which decides
	// whether the arc is to be drawn in counter clockwise or
	// in a clockwise direction. False for clockwise.
	this.draw = function() {
		if (useBitmap) {
			offsetX = this.x;
			offsetY = this.y;
			if (rotate) {
				rotationX = this.x + this.width / 2;
				rotationY = this.y + this.height / 2;		
				ctx.save();
				ctx.translate(rotationX, rotationY);
				ctx.rotate((Math.PI / 180) * this.rotation);
				offsetX = 0;
				offsetY = 0
			}
			ctx.globalAlpha = this.opacity;
			ctx.drawImage(particleImage, offsetX, offsetY, this.width, this.height);
			if (rotate) {
				ctx.restore();
			}
			//
			//ctx.drawImage(particleImage, this.x - (this.width / 2), this.y - (this.height / 2), this.width, this.height);
		} else {
			ctx.fillStyle = "rgba(255, 255, 255, " + this.opacity + ")";
			ctx.beginPath();
			ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
	
			// Fill the color to the arc that we just created
			ctx.fill();
		}
	}
}

// Function to draw everything on the canvas that we'll use when
// animating the whole scene.
function draw() {

	// Call the paintCanvas function here so that our canvas
	// will get re-painted in each next frame
	paintCanvas();

	// Call the function that will draw the balls using a loop
	for (var i = 0; i < particles.length; i++) {
		p = particles[i];
		p.draw();
	}
	
	//Finally call the update function
	update();
}

// Give every particle some life
function update() {

	// In this function, we are first going to update every
	// particle's position according to their velocities
	for (var i = 0; i < particles.length; i++) {
		p = particles[i];

		// Change the velocities
		p.x += p.vx;
		p.y += p.vy + (particleWeight * (9.82 / fps));

		// We don't want to make the particles leave the
		// area, so just change their position when they
		// touch the walls of the window
		if(p.x + p.radius > W)
			p.x = p.radius;

		else if(p.x - p.radius < 0) {
			p.x = W - p.radius;
		}

		if(p.y + p.radius > H)
			p.y = p.radius;

		else if(p.y - p.radius < 0) {
			p.y = H - p.radius;
		}

		// Now we need to make them attract each other
		// so first, we'll check the distance between
		// them and compare it to the minDist we have
		// already set

		// We will need another loop so that each
		// particle can be compared to every other particle
		// except itself
		if (interact) {
			for(var j = i + 1; j < particles.length; j++) {
				p2 = particles[j];
				distance(p, p2);
			}
		}
		
		if (sizeCycle) {
			p.radius = ((Math.sin (((time / (fps * fadeCycleTime)) - (Math.PI * p.originalRadius * fps))) + minRadius) / radiusDamper) * 10;
			p.width = p.radius;
			p.height = p.radius;
		}
		
		if (rotate) {
			p.rotation = p.rotation + p.rotationSpeed;
		}
		
		if (randomOpacity) {
			newOpacity = (Math.sin (((time / (fps * fadeCycleTime)) - (Math.PI * p.originalOpacity * fps))) + 1) / opacityDamper;
		} else {
			newOpacity = p.radius / maxRadius / opacityDamper;	
		}
		p.opacity = newOpacity;
		
		//console.log("radius: " + p.radius + ", opacity: " + p.opacity);
	}
	
	time ++;
}

// Distance calculator between two particles
function distance(p1, p2) {
	var dist,
		dx = p1.x - p2.x;
		dy = p1.y - p2.y;

	dist = Math.sqrt(dx*dx + dy*dy);

	// Draw the line when distance is smaller
	// than the minimum distance
	if(dist <= minDist && interact) {

		// Draw the line
		if (drawLines) {
			ctx.beginPath();
			ctx.strokeStyle = "rgba(255,255,255,"+ (0.75-dist/minDist) +")";
			ctx.moveTo(p1.x, p1.y);
			ctx.lineTo(p2.x, p2.y);
			ctx.stroke();
			ctx.closePath();
		}

		if (accelerate) {
			// Some acceleration for the partcles
			// depending upon their distance
			var ax = dx/100000,
				ay = dy/100000;
	
			// Apply the acceleration on the particles
			p1.vx -= ax;
			p1.vy -= ay;
	
			p2.vx += ax;
			p2.vy += ay;
		}		
	}
}

// Start the main animation loop using requestAnimFrame
function animloop() {
	draw();
	requestAnimFrame(animloop);
}