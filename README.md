#Bonus

Le responsable du catalogue souhaite conserver un historique des changements de prix lors
des imports de mercuriale. Cela lui permettra de fournir ces données aux équipes DATA pour
des analyses ultérieures. Quels implémentations possibles proposerais-tu a ton Tech Lead si tu
devais faire ces modifications ? Utilise des schémas/ diagrammes pour t
 étayer ton propos.
 
#Réponse:

on fait réference à l'audit trail, pour sauvegarder toutes les modifications apporté à l'entité Produit.
Doctrine fournit une liste d'evenement (LifeCycle Events) qu'on peut utiliser pour pouvoir manager le produit.

Concretement, on va definir une classe qui va écouter à l'évenement postUpdate Doctrine. on rajoute l'attribut AsEntityListener 
pour qu'elle soit prise en compte comme un évenement. cette dernier devra vérifier s'il y a un changement du prix du produit, et le dans une nouvelle table AuditLog
