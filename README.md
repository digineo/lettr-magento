Newsletter Export Script für Magento
====================================
    Digineo GmbH 2009 | www.digineo.de
    Author: Tim Kretschmer
    Version 1.0
    Lizenz: GNU 3


1. Installation
---------------
Führen Sie die Dateien mit Ihrer Magento Installation zusammen.
Die Datei export.php muss in den Ordner /newsletter_export/ kopiert werden.

2. Bedienung
------------
Ändern Sie als allererstes die Zugangsdaten für den Aufruf des Skriptes ($user und $password).
Erstellen Sie einen API Benutzer. Dies können sie unter System->Webservices->Users machen.
Sie müssen diesem User eine Role zuweisen unter System->Webservices->Roles. Er muss volle Rechte bekommen.
Tragen Sie die Webservices-Zugangsdaten($api_name und $api_pass) in das Skript ein.	
	
Der Aufruf des Exportes erfolgt  über http://USER:PASSWORT@ihr-shop.de/newsletter_export/export.php 	 

Beachten Sie bitte, dass die zu exportierenden E-Mailadressen als <approved = 1> in unser System übertragen werden.
Sie müssen daher berechtigt sein, an die Empfänger E-Mails zu versenden.