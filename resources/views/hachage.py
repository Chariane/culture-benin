import hashlib
import os
mot_de_passe=input("Entrez votre mot de passe : ")
message_stres="MonMessageSecret123"
print("Message STRES enrégistré en mémoire.")
sel=os.urandom(16)
print(f"Sel généré : {sel.hex()}")
hachage=hashlib.sha256(sel+mot_de_passe.encode()).hexdigest() 
print(f"Hachage du mot de passe salé : {hachage}") 
print("\n=== Vérification du mot de passe===") 
mot_de_passe_test=input("Entrez à nouveau votre mot de passe :") 
hachage_test=hashlib.sha256(sel+mot_de_passe_test.encode()).hexdigest() 
if hachage==hachage_test : 
    print("Mot de passe correct. Identité vérifiée.") 
else : 
    print("Mot de passe incorrect. Accès refusé.")