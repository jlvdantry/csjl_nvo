## Generar claves para un usuario:
openssl genrsa -out privada$1.key 1024
openssl rsa -in privada$1.key -pubout -out publica$1.key

## genera requerimiento del certificado para el usuairo
echo "genera requerimiento del certificado para el usuairo"
openssl  req -new -key privada$1.key -out req$1.pem -config CAconfig.conf

## Firmar el requerimiento y generar el certificado del usuario:
openssl x509 -inform PEM -outform PEM -keyform PEM -CAform PEM -CAkeyform PEM -in req$1.pem -out cert$1.cer -days 365 -req -CA ca.cer -CAkey CAprivada.key -sha1 -CAcreateserial -text

