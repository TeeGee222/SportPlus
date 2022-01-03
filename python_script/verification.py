#!C:\Users\Administrateur\AppData\Local\Programs\Python\Python39 python
import mysql.connector as MC
import smtplib
from email.mime.text import MIMEText
import sys
import random

def envoi_mail(message:str, destinataire):

    msg = MIMEText(message + '\n\n\n\n' + 'Service de notification proposé par 0B1')
    msg['Subject'] = 'Sport+ Notification'
    serveur = smtplib.SMTP('smtp.gmail.com', 587)    # Connexion au serveur sortant (en précisant son nom et son port)
    serveur.starttls()    # Spécification de la sécurisation
    serveur.login("SportplusNoReply@gmail.com", "2003tag23")    # Authentification
    serveur.sendmail("SportplusNoReply@gmail.com", destinataire, msg.as_string())    ## Envoi du message
    serveur.quit()    ## Déconnexion du serveur

# connexion à la base de donnée
connection = MC.connect(host = '92.151.99.204', database = 'sport+', user = 'aubin', password='2003tag23')
cursor = connection.cursor()
print("connection done")

# RECUPERER LES INFOS DE CELUI QUI VEUX VERIFIER (THOMAS ME L'ENVOIE SOUS FORME DE LISTE)
id_ = sys.argv[1]
#id_ = 14524
nom = sys.argv[2]
#nom = 'Thomas'
mail = sys.argv[3]
#mail = 'thomadgllt@gmail.com'


# CREATION DU NOMBRE ALEATOIRE
verif = ''
for i in range(5):
    verif = verif + str(random.randint(0,9))

# ENVOYER LE MAIL 
message = f"Bonjour {nom} !\n\n Ta demande de vérification a bien été reçue.\n Voici le code à 5 chiffres que tu dois copier sur Sport+ : ## {verif} ## \n\n Si tu n'arrive pas à lire ce mail, bah débrouille toi. \n Cordialement, l'équipe Sport+"

envoi_mail(message, mail)
req = f"UPDATE membres SET verification = {verif} WHERE idmbrs = {id_};"
cursor.execute(req)
connection.commit()

connection.close()