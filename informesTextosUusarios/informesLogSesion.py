from datetime import datetime, timezone, date, timedelta
import pandas as pd
import smtplib, ssl
from email.mime.text import  MIMEText
from email.mime.multipart import MIMEMultipart
from email.mime.base import  MIMEBase
from email import  encoders
from email.utils import make_msgid
import os.path
from os import path
import sys

sys.path.insert(0,'/var/www/FlaskPQR/Flask/conection')
from sqlServerConn import Connect

def envioExtraccion(correoDestino, nombre_archivo):

    #Se genera conexion con servidor Chec
    port = 465  # For SSL
    smtp_server = "mail.epm.com.co"
    #Se definen credenciales de acceso al servidor Chec
    password = '1yX8EwuICjVZ'
    sender_email = "infocomercial@chec.com.co"
    login = "CHEC\\infocomercial"
    #Aqui debe seleccionarse una descripcion en con base en el envio
    email_message = 'Se adjunta el Reporte actualizado a la fecha de los textos de usuarios que pueden ser PQRs.'
    # email_message = 'Buenas tardes. Esto es un correo automático. Con este se da inicio a los reportes diarios de logs de accesos a la plataforma de respuestas PQRs. Se adjunta el Reporte actualizado a la fecha de los accesos a la plataforma de respuestas PQR.'
    #Se configuran opciones de envio
    message = MIMEMultipart()
    message["Subject"] = 'Reporte Logs Textos PQRs'
    message["From"] = sender_email
    message["To"] = ", ".join(correoDestino)
    message.attach(MIMEText(email_message, 'plain'))
    #Se adjunta el documento que se escribio en mergeDatos()
    with  open('/var/www/FlaskPQR/Flask/informes/' + str(nombre_archivo), "rb") as adjunto: 
        contenido_adjunto = MIMEBase("application", "octect-stream")
        contenido_adjunto.set_payload(adjunto.read())

        encoders.encode_base64(contenido_adjunto)
        contenido_adjunto.add_header('Content-Disposition',
                            "documento; filename= %s" % str(nombre_archivo))
        message.attach(contenido_adjunto)
    
    # message = message.as_string()
    context = ssl._create_unverified_context()
    with smtplib.SMTP(smtp_server, port) as server:
        server.starttls(context=context)
        server.login(login, password)
        server.send_message(message)
            
    return (f"Proceso exitoso")

SGPQR = Connect()

correoDestino = ['prjchec.egomez@umanizales.edu.co']
fecha_actual = date.today()
fecha_resta = fecha_actual - timedelta(days=1)
# fecha_actual = '2021-08-11'
# fecha_resta = '2021-08-09'

nombre_archivo = 'Log_Sesion_respuestasPQR_' + str(fecha_resta) + '.csv'

logs_sesion = SGPQR.executeQuery(f"""SELECT [usuario],[permisos],[fecha]
                                FROM [SGPQR].[dbo].[log_sesion]
                                WHERE [usuario] NOT IN ('egomezhe', 'aosoriod', 'vgomezhe', 'jnarvaez', 'dospinlo')
                                AND [fecha] between ? and ?""",
                                (fecha_resta, fecha_actual))

resultArray = []
    
for log in logs_sesion:

    resultArray.append({
        'usuario': log[0],
        'permisos': log[1],
        'fecha': log[2],
    })

df_log = pd.DataFrame(resultArray)

df_log.to_csv('/var/www/FlaskPQR/Flask/informes/' + str(nombre_archivo), index=False)

envioExtraccion(correoDestino, nombre_archivo)

os.unlink('/var/www/FlaskPQR/Flask/informes/' + str(nombre_archivo))


