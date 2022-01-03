
import mysql.connector as MC
import smtplib
import time 
from email.mime.text import MIMEText

def envoi_mail(message:str, destinataire):

    msg = MIMEText(message + '\n\n\n\n' + 'Service de notification proposé par 0B1')
    msg['Subject'] = 'Sport+ Notification'
    serveur = smtplib.SMTP('smtp.gmail.com', 587)    # Connexion au serveur sortant (en précisant son nom et son port)
    serveur.starttls()    # Spécification de la sécurisation
    serveur.login("SportplusNoReply@gmail.com", "2003tag23")    # Authentification
    serveur.sendmail("SportplusNoReply@gmail.com", destinataire, msg.as_string())    ## Envoi du message
    serveur.quit()    ## Déconnexion du serveur




# contenu du msg dans bdd 
# destinataire dans bdd 
while True:
    # connexion à la base de donnée
    connection = MC.connect(host = 'lesmerdesdu92.ddns.net', database = 'sport+', user = 'aubin', password='2003tag23')
    #connection = MC.connect(host = '92.151.99.204', database = 'sport+', user = 'root')
    cursor = connection.cursor()
    print("connection done")

    req = 'SELECT idmbrs FROM membres;'
    cursor.execute(req)
    nb_user = cursor.fetchall()

    for i in range(len(nb_user)):
        n = i + 14523
        req = f'SELECT notification FROM membres WHERE idmbrs={n};'
        cursor.execute(req)
        notif = cursor.fetchall()
        print("notif", notif)
        if notif[0][0]:
            req = f'SELECT mail FROM membres WHERE idmbrs={n};'
            cursor.execute(req)
            mail = cursor.fetchall()
            print(mail, "mail")
            notif = notif[0][0]
            mail = mail[0][0]
            envoi_mail(notif, mail)
            req = f"UPDATE membres SET notification = NULL WHERE idmbrs = {n};"
            cursor.execute(req)
            connection.commit()

        else:
            print("pas de notif")
            pass
        
    connection.close()
    time.sleep(30)

