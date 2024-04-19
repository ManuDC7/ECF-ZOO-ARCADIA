# GUIDE DE DEPLOIEMENT de l'application web en local sur MacOS

![Design preview for my ecf](https://image.noelshack.com/fichiers/2024/09/1/1708937402-screen-arcadia.jpg)

## INTRODUCTION

Ce guide vous fournira des instructions détaillées sur la façon de déployer l'application web localement sur un système macOS. Nous utiliserons plusieurs outils et technologies couramment utilisés dans le développement web.

## PREREQUIS

Avant de commencer le processus de déploiement, assurez-vous d'avoir les éléments suivants installés sur votre système macOS :

- **Homebrew** : Un gestionnaire de paquets pour macOS.

## ETAPE 1: Installation de Podman (Facultatif)

Podman est un gestionnaire de conteneurs conçu pour les environnements Linux. Vous pouvez l'installer via Homebrew pour maintenir un environnement de développement propre.

`brew install podman`

Après l'installation, vous pouvez démarrer Podman en exécutant la commande suivante dans votre terminal :

`podman system start`

## ETAPE 2: Installation de MongoDB et SQLite

L'application web utilise deux bases de données : une base de données relationnelle SQLite et une base de données non relationnelle MongoDB. Vous pouvez les installer en utilisant Homebrew :

`brew install sqlite`
`brew install mongodb/brew/mongodb-community`

## ETAPE 3: Installation de PHP et de son extension PDO
PHP est un langage de programmation serveur largement utilisé dans le développement web. Vous pouvez l'installer via Homebrew :

`brew install php`

Ensuite, installez l'extension PDO pour PHP, qui permet d'accéder aux bases de données relationnelles :

`brew install php-pdo`

## ETAPE 4: Installation de l'extension MongoDB pour PHP

L'extension MongoDB pour PHP est requise pour interagir avec la base de données MongoDB. Vous pouvez l'installer via Composer :

`composer require mongodb/mongodb`

## ETAPE 5: Configuration de Postfix

Postfix est un serveur de messagerie open-source utilisé pour router et distribuer le emails. Vous pouvez configurer Postfix sur votre système macOS comme suis:

1- Installation de Postfix :

Vous pouvez installer Postfix via Homebrew en exécutant la commande suivante dans votre terminal :

`brew install postfix`

2- Après l'installation, vous devez configurer Postfix pour qu'il puisse envoyer des courriers électroniques. Pour cela, ouvrez le fichier de configuration principal de Postfix en utilisant nano ou vim :

`sudo nano /etc/postfix/main.cf`

3- Configurer les paramètres de base :

Vous devez configurer au moins deux paramètres pour que Postfix fonctionne correctement :

- myhostname : Définissez le nom d'hôte de votre serveur. Vous pouvez utiliser le nom de domaine complet de votre machine.

- myorigin : Définissez l'adresse électronique de l'expéditeur par défaut pour les courriers électroniques sortants. Par exemple, myorigin = example.com.

4- Redémarrage de Postfix :

Une fois que vous avez configuré les paramètres, enregistrez le fichier de configuration et redémarrez Postfix pour appliquer les modifications :

`sudo postfix reload`

5- Test de configuration :

Pour tester si Postfix fonctionne correctement, vous pouvez envoyer un courrier électronique de test en utilisant la commande mail :

`echo "Test email from Postfix" | mail -s "Test Postfix" votre@email.com`

## ETAPE 6: Utilisation de XAMPP

XAMPP est une suite de logiciels comprenant Apache, MySQL, PHP et Perl, qui permet de configurer facilement un environnement de développement local.

- Téléchargez XAMPP depuis le [site officiel](https://www.apachefriends.org/fr/index.html).
- Suivez les instructions d'installation fournies sur le site officiel pour installer XAMPP sur votre système macOS.

## ETAPE 7: Configuration de XAMPP

Après l'installation de XAMPP, vous devez configurer PHP pour activer les fonctionnalités nécessaires. Pour ce faire, suivez ces étapes :

1 - Trouvez le fichier **php.ini** dans le répertoire d'installation de XAMPP (`/Applications/XAMPP/etc`).

2 - Ouvrez le fichier **php.ini** dans un éditeur de texte.

3 - Décommentez les lignes suivantes en supprimant le point-virgule (**;**) au début de chaque ligne:

`extension=mongodb.so`
`extension=sqlite3`
`extension=pdo_mysql`

4 - Enregistrez les modifications et fermez le fichier **php.ini**.

5 - Redémarrez XAMPP en exécutant la commande suivante depuis le répertoire d'installation de XAMPP :

`sudo ./xampp restart`

## ETAPE 8: Clonage du repository GitHub

Une fois XAMPP configuré, vous pouvez cloner le repository GitHub de l'application web dans le répertoire **htdocs** de XAMPP :

`cd /Applications/XAMPP/htdocs`
`git clone URL_DU_REPO`

Le repo va alors se cloner dans notre répertoire htdocs de XAMPP.

## ETAPE 9: Connexion à l'application web

Vous pouvez maintenant ouvrir votre navigateur web et accéder à l'application web en utilisant l'URL suivante :

`http://localhost/NOM_DE_VOTRE_PROJET/`

### ETAPE facultative : En cas d'erreur lors du démarrage d'Apache ou de tentative d'écriture dans une base de données en lecture seule

Si vous rencontrez une erreur lors du démarrage d'Apache via XAMPP ou si vous obtenez une erreur de type 'SQLSTATE[HY000]: General error: 8 attempt to write a readonly database', voici une solution pour résoudre ces problèmes :


1 - Erreur lors du démarrage d'Apache :

Il est possible qu'un autre processus écoute déjà sur le port 80, ce qui empêche Apache de démarrer correctement. Vous pouvez suivre ces étapes pour vérifier les processus en cours d'exécution sur le port 80 et les terminer si nécessaire :

Ouvrez un terminal sur votre système macOS.

Utilisez la commande suivante pour afficher les processus écoutant sur le port 80 :

- Ouvrez un terminal sur votre système macOS.

- Utilisez la commande suivante pour afficher les processus écoutant sur le port 80 :

`sudo lsof -i :80`

- Notez les ID de processus (PID) des processus qui écoutent sur le port 80.

- Utilisez la commande kill avec les PID des processus pour les terminer :

`sudo kill PID`

- Remplacez PID par le PID du processus que vous souhaitez terminer.

Une fois que vous avez terminé les processus en cours d'exécution sur le port 80, redémarrez XAMPP en utilisant la commande suivante depuis le répertoire d'installation de XAMPP :

`sudo ./xampp restart`

2 - Erreur de tentative d'écriture dans une base de données en lecture seule :

Si vous rencontrez une erreur de type 'SQLSTATE[HY000]: General error: 8 attempt to write a readonly database', cela signifie généralement que l'utilisateur exécutant le serveur web n'a pas les permissions d'écriture sur la base de données ou le fichier de base de données.

- Assurez-vous que l'utilisateur exécutant le serveur web (généralement 'www-data' sur macOS) dispose des permissions d'écriture sur le fichier de base de données ou sur le répertoire contenant la base de données.

- Vous pouvez utiliser la commande chmod pour modifier les permissions du fichier ou du répertoire :

`sudo chmod g+w NOM_DU_FICHIER_OU_REPERTOIRE`

- Puis vous pouvez changer le groupe du fichier de base de données pour "daemon":

`sudo chown :daemon NOM_DU_FICHIER_OU_REPERTOIRE`

- Après avoir modifié les permissions, redémarrez XAMPP pour appliquer les modifications.

# FIN


