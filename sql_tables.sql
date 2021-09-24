CREATE TABLE category 
(
	id     	   	INT            			 AUTO_INCREMENT, 
	title	   	VARCHAR(45)   			 NOT NULL, 
	description	VARCHAR(225)   			 NOT NULL, 
	status     	enum('SHOW','HIDE')      	 DEFAULT 'SHOW', 
	CONSTRAINT category_pk PRIMARY KEY (id)
);

CREATE TABLE items 
(
	id     		INT            			AUTO_INCREMENT, 
	title	    	VARCHAR(225)   			NOT NULL, 
	description	TEXT   				NOT NULL, 
	price       	DECIMAL(8,2)   			NOT NULL, 
	cat_id        	INT			   	NOT NULL, 
	status        	enum('SHOW','HIDE')     	DEFAULT 'SHOW', 
	image		VARCHAR(225)     		NOT NULL, 
	CONSTRAINT items_pk PRIMARY KEY (id),
	CONSTRAINT category_items_fk_items FOREIGN KEY (cat_id)
		REFERENCES category(id)
);

CREATE TABLE members 
(
	id     		    INT            		AUTO_INCREMENT,
	first_name	    VARCHAR(45)   		NOT NULL,
	last_name	    VARCHAR(45)   		NOT NULL,
	username	    VARCHAR(45)   		NOT NULL,
	password	    VARCHAR(45)   		NOT NULL,
	email		    VARCHAR(45)   		NOT NULL,
	CONSTRAINT members_pk PRIMARY KEY (id)
);

CREATE TABLE track 
(
	id     		INT            			AUTO_INCREMENT,
	item_id	    	int   				NOT NULL,
	date_view	date   				NOT NULL,
	count	    	int   				NOT NULL,
	CONSTRAINT track_pk PRIMARY KEY (id)
);

INSERT INTO category (`title`, `description`, `status`) values 
("Electronics","Associated passive electrical components, and interconnection technologies","SHOW"),
("Mobile Phones","Also known as a hand phone, cell phone, or cellular telephone","SHOW"),
("Camera","Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam efficitur commodo vestibulum","SHOW"),
("Gaming Console","Donec tempus fermentum nisi vehicula dignissim. Sed rhoncus ullamcorper dolor eu dictum","SHOW"),
("Music Player","An electronic device that can play MP3 digital audio files","SHOW"),
("Television","An electronic device used to receive sound and images that people watch.","SHOW"),
("Laptop","A computer that is portable and suitable for use while traveling.","SHOW");

INSERT INTO items (`title`, `description`, `price`, `image`, `cat_id`, `status`) values 
("Canon EOS Rebel","Budget-friendly DSLR with an 18MP APS-C-size sensor. It uses Canon's DIGIC 4+ image processor which offers an ISO range of 100-6400, expandable to 12800, as well as 3p fps burst shooting",900,"assets/img/5fa078227c5467.71843571.jpg",3,"SHOW"),
("Iphone 12","Apple's latest phone integrated with high-end A-Bionic Chipset.",1200,"assets/img/5f9b52b25985a0.43624577.jpg",2,"SHOW"),
("Iphone 12","Pro Widescreen Apple phone, with an aeronomic display.",1500,"assets/img/5f9b5398a050f0.25789212.png",2,"SHOW"),
("Pixel 5","Essentially a newer version of the 765G, this chipset features a faster GPU and boosts the clock speed of the main core in the CPU. Google Pixel",799,"assets/img/5f9e12a1dcc4f7.81659874.png",2,"SHOW"),
("Pixel 4a","With its adaptive battery, Google Pixel 4a reduces power to apps you rarely use. Shop now! The Pixel 4a camera has HDR+, Night Sight, and more.",479,"assets/img/5f9b7079330fc5.79726336.jpg",2,"SHOW"),
("Sony A100","High End Camera",500,"assets/img/5f9b7231097c12.93696404.jpg",3,"SHOW"),
("Sony Ps Vita","The latest gaming console created by sony corporation.",400,"assets/img/5f9db2cf8e5323.33937304.jpg",4,"SHOW"),
("Sony nw-a55","As an entry-level portable player, the NW-A55L delivers an entertaining experience with a detailed and natural-sounding Hi-Res audio performance.",350,"assets/img/5f9d96ee175375.29236958.jpg",5,"SHOW"),
("Samsung S20","The smallest of the S20 family, comes with a 6.2-inch display. Under the hood is Snapdragon 865/Exynos 990 chipset with 12GB RAM and 128GB storage",1350,"assets/img/5fa077af2a3f10.25996406.jpg",2,"SHOW"),
("Nintendo switch","Hybrid video game console, consisting of a console unit, a dock, and two Joy-Con controllers",400,"assets/img/5fa07a6cc70a07.51105951.jpg",4,"SHOW"),
("Game Boy Advance","32-bit handheld game console developed, manufactured and marketed by Nintendo as the successor to the Game Boy Color.",60,"assets/img/5fa082158f5ee7.31495734.jpg",4,"SHOW"),
("Sony xperia 1 ii","It packs the latest cutting-edge technologies and a camera developed with Sony´s Alpha camera engineers to deliver exceptionally fast autofocus in a smartphone.",1600,"assets/img/5fa082b8e21438.89957537.jpg",2,"SHOW"),
("Sony KD55X750H 55 4K HDR LED Smart TV","Experience thrilling games, photos, music, movies, Internet and apps in beautiful colour and incredible 4K clarity",750,"assets/img/5fa087a6c1f1a7.62499626.png",6,"SHOW"),
("Apple MacBook Pro (2020) w/ Touch Bar 13.3 - Space Grey","1.4GHz quadcore 8th generation Intel Core i5, Turbo Boost up to 3.9GHz, with 128MB of eDRAM",1699,"assets/img/5fa094d6a31bb5.33337519.png",7,"SHOW"),
("Lenovo IdeaPad 3 15.6 Touchscreen Laptop","1.2 GHz Intel Core i3-1005G1 dual core processor with 8GB RAM provides great performance for your daily tasks",599,"assets/img/5fa095ad0fb2f4.39211282.png",7,"SHOW"),
("HP 15.6 Gaming Laptop - Black","3.0GHz AMD Ryzen 5 4600H processor and 8GB DDR4-3200 SDRAM can run high-end games and applications without slowing down",899,"assets/img/5fa0964d7a5d19.17002620.png",7,"SHOW"),
("Sony BRAVIA 65 4K UHD HDR OLED Android Smart TV","65 4K Ultra HD display with 3840 x 2160 native resolution lets you enjoy movies, TV shows, games, and sports in stunning clarity",2499,"assets/img/5fa098a914ca37.75268040.png",6,"SHOW");

INSERT INTO members (`first_name`, `last_name`, `username`, `password`,`email`) values 
("Allan John","Valiente","allan______","********","allanjohn.valiente@georgebrown.ca");

/*Sample Data*/
INSERT INTO items (`title`, `description`, `price`, `image`, `cat_id`, `status`) values 
("Sample1","Description1",900,"",1,"SHOW"),
("Sample2","Description2",900,"",1,"SHOW");

DELETE FROM items WHERE cat_id=1;

INSERT INTO items (`title`, `description`, `price`, `image`, `cat_id`, `status`) values
('iPod touch', 'iPod touch gives you a beautiful canvas for your messages, photos, videos and more. Everything is sharp, vivid and lifelike. All on a device that’s 6.1 mm thin and just 88 grams, so you can take it anywhere.', 249, 'assets/img/5fd1ce006f19f6.95540577.jpg', 5, 'SHOW')

