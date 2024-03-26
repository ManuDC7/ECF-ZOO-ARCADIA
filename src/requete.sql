CREATE TABLE services (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    name VARCHAR(100) NOT NULL UNIQUE, 
    description VARCHAR(255) NOT NULL, 
    slug VARCHAR(255) NOT NULL
    );

CREATE TABLE comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    pseudo VARCHAR(20) UNIQUE NOT NULL, 
    message VARCHAR(255) NOT NULL, 
    validate VARCHAR(5) NOT NULL
    );

CREATE TABLE users (
    userId INTEGER PRIMARY KEY AUTOINCREMENT, 
    email VARCHAR(50) NOT NULL, 
    firstname VARCHAR(50) NOT NULL, 
    password_hash VARCHAR(20) NOT NULL);

CREATE TABLE roles (
    roleId INTEGER PRIMARY KEY AUTOINCREMENT, 
    label VARCHAR(20) NOT NULL, 
    userId INTEGER NOT NULL, 
    FOREIGN KEY(userId) REFERENCES users(userId));

CREATE TABLE housings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(20) UNIQUE NOT NULL, 
    description TEXT NOT NULL, 
    slug VARCHAR(255) NOT NULL, 
    comments VARCHAR(255)
    );

CREATE TABLE opening (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    day VARCHAR(10) UNIQUE, 
    hours VARCHAR(15) NOT NULL
    );

CREATE TABLE reports (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date VARCHAR(10) NOT NULL, 
    report TEXT NOT NULL, 
    animal_id INTEGER, 
    FOREIGN KEY (animal_id) REFERENCES animals(id)
    );

CREATE TABLE animals (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    firstname VARCHAR(20) NOT NULL, 
    breed VARCHAR(50) NOT NULL, 
    slug VARCHAR(255) NOT NULL, 
    description TEXT, 
    housing VACHAR(20) NOT NULL, 
    FOREIGN KEY (housing) REFERENCES housings(name)
    );

CREATE TABLE foods (
    id INTEGER PRIMARY KEY AUTOINCREMENT, 
    date VARCHAR(10) NOT NULL, 
    hours VARCHAR(5) NOT NULL,
    state VARCHAR(255), 
    food VARCHAR(50) NOT NULL, 
    weight VARCHAR(20) NOT NULL, 
    animal_id INTEGER NOT NULL, 
    FOREIGN KEY (animal_id) REFERENCES animals(id)
    );

INSERT INTO services (name, description, slug)
VALUES 
    ('Restauration', 'Au cœur d''une réserve naturelle restaurée, les visiteurs déambulent parmi des enclos spacieux, émerveillés par la diversité de la faune. Le restaurant, niché au sein de ce havre préservé, propose une expérience gastronomique au son apaisant de la nature. Des efforts dédiés à la préservation de l''écosystème se reflètent dans chaque aspect, offrant une communion harmonieuse entre l''homme et la vie sauvage.', 'https://image.noelshack.com/fichiers/2024/08/6/1708782622-restauration.jpg'),
    ('Visite guidée', 'Nous débutons par le marais, où la faune aquatique prospère dans son environnement naturel. Ensuite, plongeons dans la jungle luxuriante, où des espèces exotiques cohabitent harmonieusement. Enfin, la savane dévoile sa majesté avec ses vastes plaines et ses animaux emblématiques. Suivez notre guide dans cette aventure immersive, où chaque habitat raconte une histoire unique de conservation et de biodiversité.', 'https://image.noelshack.com/fichiers/2024/08/6/1708782508-guided-visite.jpg'),
    ('Visite en petit train', 'Asseyez-vous, détendez-vous et laissez-vous emporter. Le voyage débute par un trajet à travers les marais, où vous découvrirez la vie aquatique en toute quiétude. Ensuite, le train s''aventure dans la jungle luxuriante, offrant des vues imprenables sur des espèces exotiques évoluant librement. La dernière étape nous transporte à travers la savane, où des plaines vastes et des animaux majestueux défilent sous vos yeux.', 'https://image.noelshack.com/fichiers/2024/08/6/1708782633-train.jpg');

INSERT INTO comments (pseudo, message, validate)
VALUES 
    ('Antoine D.', 'Zoo au top du top ! super après midi passé en famille, nous avons bien mangé et le guide est super !', true);

INSERT INTO users (userId, email, firstname, password_hash)
VALUES 
    ('', 'josearcadia@hotmail.fr', 'José', 'arcadmin');
    ('', 'samheldib@gmail.fr', 'Samir', 'test');
    ('', 'nadegeletelier@outlook.fr', 'Nadège', 'test');

INSERT INTO roles (roleId, label, userId)
VALUES
    ('', 'Administrator', 1);
    ('', 'Employee', 2);
    ('', 'Veterinarian', 3);

INSERT INTO housings (name, description, slug)
VALUES 
    ('marais', 'Nous vous présentons les animaux présent dans notre marais. La magie prend vie à travers une biodiversité exceptionnelle. Explorez cet habitat et découvrez la grâce des flamants roses qui évoluent en toute liberté, observez nos crocodiles qui se cachent astucieusement dans les eaux sombres, et laissez-vous émerveiller par la variété éclatante de créatures tropicales qui peuplent cet environnement unique. Rencontrez nos grenouilles colorées et serpents mystérieux, tous contribuant à équilibrer cet écosystème fascinant. Bienvenue dans nos marécages, où chaque pas révèle une nouvelle facette captivante de ce monde !', 'https://image.noelshack.com/fichiers/2024/08/5/1708706959-swamp.jpg'),
    ('savane', 'Nous vous présentons les animaux de notre vaste savane. Découvrez la majesté de nos éléphants paisibles se déplaçant en troupeaux, observez les lions majestueux règnant en souverains des plaines, et appréciez la grâce des girafes se penchant gracieusement pour atteindre les feuilles les plus hautes. Notre savane abrite une diversité de créatures, des zèbres aux antilopes, créant un écosystème dynamique et équilibré.', 'https://image.noelshack.com/fichiers/2024/08/5/1708707166-savane.jpg'),
    ('jungle', 'Nous vous présentons les animaux de notre jungle luxuriante. Explorez notre refuge où la vie sauvage prospère dans un écosystème exotique. Rencontrez nos amis à fourrure tels que les singes malicieux et les paresseux paisibles se balançant entre les branches. Admirez la beauté majestueuse des tigres rayés et la vivacité des perroquets aux plumes éclatantes. Chaque recoin de notre jungle abrite des créatures étonnantes, des serpents colorés aux papillons féériques.', 'https://image.noelshack.com/fichiers/2024/08/5/1708707191-jungle.jpg');

INSERT INTO opening (day, hours)
VALUES 
    ('lundi', '10H00 - 18H00'),
    ('mardi', '10H00 - 18H00'),
    ('mercredi', '09H00 - 19H00'),
    ('jeudi', '10H00 - 18H00'),
    ('vendredi', '10H00 - 18H00'),
    ('samedi', '09H00 - 19H00');

INSERT INTO animals (firstname, breed, slug, description, housing)
VALUES 
    ('odile', 'crocodile', 'https://image.noelshack.com/fichiers/2024/08/5/1708708083-crocodile.jpg', 'Odile est un crocodile imposant, avec une peau rugueuse et des écailles épaisses qui lui confèrent une apparence redoutable. Son corps fuselé et sa mâchoire puissante en font un prédateur redoutable des eaux douces. Ses yeux perçants, semblables à des fentes, surveillent silencieusement les berges des rivières et des marécages où elle chasse. Odile se déplace avec une grâce sinistre dans les eaux sombres, se fondant parfaitement dans son environnement aquatique. Elle traque sa proie avec patience et détermination, attendant le moment opportun pour attaquer avec précision. Malgré sa réputation de prédateur redoutable, Odile est également un animal solitaire, préférant chasser et se reposer seule. Elle passe la plupart de ses journées à se prélasser au soleil sur les rives tranquilles, prête à défendre son territoire en cas de besoin.', 'marais'),
    ('victor', 'boa constrictor', 'https://image.noelshack.com/fichiers/2024/08/5/1708708051-snake.jpg', 'Victor est un boa constrictor impressionnant, avec un corps long et musclé qui serpente gracieusement à travers son habitat. Sa peau lisse et brillante est ornée de motifs complexes, allant du brun foncé au beige, lui permettant de se fondre parfaitement dans son environnement. Ses yeux perçants, souvent comparés à des joyaux étincelants, témoignent de sa vigilance constante. Victor se déplace avec une fluidité sinistre, traquant silencieusement sa proie avant de la ceinturer dans ses puissantes anneaux. Malgré sa réputation de prédateur redoutable, Victor est un animal paisible et discret, préférant éviter les confrontations inutiles. Il passe la plupart de ses journées à se reposer, dissimulé dans des buissons, attendant patiemment sa prochaine opportunité de chasse.', 'marais'),
    ('léon', 'lion', 'https://image.noelshack.com/fichiers/2024/08/5/1708708015-lion.jpg', 'Léon est un lion majestueux et imposant, avec une crinière flamboyante qui encadre son visage noble. Sa fourrure dorée brille sous le soleil de la savane, donnant à son pelage une aura de puissance et de beauté sauvage. Les yeux perçants de Leon reflètent la détermination et la fermeté de son caractère, témoignant de sa place dominante. Leon se déplace avec une grâce souveraine, faisant régner sa présence royale sur son territoire. Il chasse avec agilité et stratégie, traquant sa proie avec une précision redoutable. Malgré sa réputation de prédateur redoutable, Leon est également un leader bienveillant pour sa fière famille de lions, veillant sur eux avec vigilance et affection.', 'savane'),
    ('olaf', 'girafe', 'https://image.noelshack.com/fichiers/2024/08/5/1708707982-giraffe.jpg', 'Olaf est une girafe élégante et gracieuse, avec un long cou élancé et des taches distinctives réparties sur son pelage couleur fauve. Ses grands yeux doux reflètent la tranquillité et la sérénité de son habitat naturel. Olaf se déplace avec une démarche calme et paisible à travers les vastes étendues de la savane africaine, cherchant des feuilles tendres dans les cimes des arbres acacias. Sa silhouette élancée, remarquable au loin, fait de lui un emblème reconnaissable de la savane. Malgré sa taille imposante, Olaf est un animal doux et docile, préférant la tranquillité à la confrontation. Il est peu souvent en compagnie de ses semblables, échangeant des gestes agressifs de coups de tête.', 'savane'),
    ('léonard', 'léopard', 'https://image.noelshack.com/fichiers/2024/08/5/1708707951-leopard.jpg', "Léonard le léopard est le souverain indiscuté des terres sauvages, avec son pelage doré tacheté et ses yeux perçants scrutant l'horizon. Agile et gracieux, il se déplace avec une élégance naturelle à travers la jungle, se fondant dans les hautes herbes ondulantes. Sa démarche révèle une assurance inébranlable, tandis qu'il grimpe aux arbres avec une agilité déconcertante pour guetter ses proies. Chasseur redoutable, il utilise sa rapidité fulgurante et sa ruse légendaire pour capturer sa nourriture, démontrant ainsi sa place prédominante au sommet de la chaîne alimentaire. Malgré sa nature solitaire, il peut parfois se joindre à d'autres félins pour chasser ou partager un moment de camaraderie sous la lueur de la lune. Léonard incarne la dualité de la nature sauvage, à la fois puissant prédateur et être sensible, captivant par sa beauté mystérieuse et sa majesté indéniable.", 'jungle'),
    ('adan', 'toucan', 'https://image.noelshack.com/fichiers/2024/08/5/1708707899-toucan.jpg', 'Adan est un toucan majestueux au plumage éclatant et coloré, orné de nuances vives de rouge, de jaune et de vert. Son bec distinctif, long et fin, est un noir profond, avec des touches de rouge vif à la base. Ses grands yeux bruns sont vifs et curieux, reflétant son intelligence et sa vivacité. Adan est un oiseau remarquablement gracieux, avec une démarche élégante et un vol puissant et agile. Il vit dans les profondeurs de la luxuriante jungle tropicale, où il passe ses journées à explorer les hauteurs des arbres et à se nourrir de fruits juteux, de baies et parfois même de petits insectes. Sa voix résonne à travers la canopée sous la forme de croassements rauques et mélodieux, ajoutant une touche de magie à la symphonie naturelle de la jungle. Malgré sa taille imposante, Adan est un oiseau doux et sociable, souvent vu en compagnie de ses semblables, se lançant dans des jeux aériens et des duels de becs amicaux. Sa personnalité enjouée et son charme irrésistible en font une figure appréciée de la faune de la jungle, un symbole de la beauté et de la diversité de la nature tropicale.', 'jungle');

DELETE FROM users WHERE firstname = 'Samir';
DELETE FROM users WHERE firstname = 'Nadège';