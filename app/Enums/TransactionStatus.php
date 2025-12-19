<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case AUTHENTICATION_FAILED = 'AUTHENTICATION_FAILED';
    case BLOCKED = 'BLOCKED';
    case DENIED = 'DENIED';
    case AUTHORIZED_AND_PENDING = 'AUTHORIZED_AND_PENDING';
    case REFUSED = 'REFUSED';
    case EXPIRED = 'EXPIRED';
    case CANCELLED = 'CANCELLED';
    case AUTHORIZED = 'AUTHORIZED';
    case CAPTURE_REQUESTED = 'CAPTURE_REQUESTED';
    case CAPTURED = 'CAPTURED';
    case PARTIALLY_CAPTURED = 'PARTIALLY_CAPTURED';
    case REFUND_REQUESTED = 'REFUND_REQUESTED';
    case REFUNDED = 'REFUNDED';
    case PARTIALLY_REFUNDED = 'PARTIALLY_REFUNDED';
    case CHARGED_BACK = 'CHARGED_BACK';
    case DISPUTE_LOST = 'DISPUTE_LOST';
    case AUTHORIZATION_REQUESTED = 'AUTHORIZATION_REQUESTED';
    case AUTHORIZATION_CANCELLED = 'AUTHORIZATION_CANCELLED';
    case REFERENCE_RENDERED = 'REFERENCE_RENDERED';
    case REFUND_REFUSED = 'REFUND_REFUSED';
    case DEBITED = 'DEBITED';
    case CREDIT_REQUESTED = 'CREDIT_REQUESTED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case CAPTURE_REFUSED = 'CAPTURE_REFUSED';
    case AWAITING_TERMINAL = 'AWAITING_TERMINAL';
    case AUTHORIZATION_CONCELLATION_REQUESTED = 'AUTHORIZATION_CONCELLATION_REQUESTED';
    case CHALLENGE_REQUESTED = 'CHALLENGE_REQUESTED';
    case SOFT_DECLINED = 'SOFT_DECLINED';
    case PENDING_PAYMENT = 'PENDING_PAYMENT';
    case SUCCEEDED = 'SUCCEEDED';

    public function label(): string
    {
        return match($this)
        {
            self::AUTHENTICATION_FAILED =>"Échec de l'authentification",
            self::BLOCKED => 'Bloqué',
            self::DENIED => 'Refusé',
            self::AUTHORIZED_AND_PENDING => 'Autorisé et en attente',
            self::REFUSED => 'Refusé',
            self::EXPIRED => 'Expiré',
            self::CANCELLED => 'Annulé',
            self::AUTHORIZED => 'Autorisé',
            self::CAPTURE_REQUESTED => 'Capture demandée',
            self::CAPTURED => 'Capturé',
            self::PARTIALLY_CAPTURED => 'Partiellement capturé',
            self::REFUND_REQUESTED => 'Remboursement demandé',
            self::REFUNDED => 'Remboursé',
            self::PARTIALLY_REFUNDED => 'Partiellement remboursé',
            self::CHARGED_BACK => 'Mise en opposition',
            self::DISPUTE_LOST => 'Litige perdu',
            self::AUTHORIZATION_REQUESTED => 'Autorisation demandée',
            self::AUTHORIZATION_CANCELLED => "Annulation de l'autorisation",
            self::REFERENCE_RENDERED => 'Référence rendue',
            self::REFUND_REFUSED => 'Remboursement refusé',
            self::DEBITED => 'Débité',
            self::CREDIT_REQUESTED => 'Crédit demandé',
            self::IN_PROGRESS => 'En cours',
            self::CAPTURE_REFUSED => 'Capture Refusée',
            self::AWAITING_TERMINAL => 'Terminal en attente',
            self::AUTHORIZATION_CONCELLATION_REQUESTED => "Demande d'annulation de l'autorisation",
            self::CHALLENGE_REQUESTED => 'Défi demandé',
            self::SOFT_DECLINED => 'Déclinées en douceur',
            self::PENDING_PAYMENT => 'Paiement en attente',
            self::SUCCEEDED => 'Paiement validé',
        };
    }

    public function description(): string
    {
        return match($this)
        {
            self::AUTHENTICATION_FAILED => "L'authentification du titulaire de la carte a échoué. La demande d'autorisation ne doit pas être soumise. Un échec de l'authentification peut être une indication possible d'un utilisateur frauduleux.",
            self::BLOCKED => "La transaction a été rejetée en raison d'un soupçon de fraude.",
            self::DENIED => "Le commerçant a refusé la tentative de paiement. Après avoir examiné les résultats du contrôle de la fraude, le commerçant a décidé de refuser le paiement.",
            self::AUTHORIZED_AND_PENDING => "Le paiement a été contesté par l'ensemble des règles relatives à la fraude et est en cours d'examen.",
            self::REFUSED => "Les raisons du refus peuvent être : une limite de crédit dépassée, une date d'expiration incorrecte ou un solde insuffisant, ou bien d'autres encore, en fonction de la méthode de paiement choisie.",
            self::EXPIRED => "La période de validité de l'autorisation de paiement a expiré. Cela se produit lorsqu'aucune demande de capture n'est soumise pour un paiement autorisé, généralement dans les 7 jours suivant l'autorisation.Note : Selon la banque émettrice du client, la période de validité de l'autorisation peut durer de 1 à 5 jours pour une carte de débit et jusqu'à 30 jours pour une carte de crédit.",
            self::CANCELLED => "Le commerçant a annulé la tentative de paiement. Seuls les paiements ayant le statut \"Autorisé\" et n'ayant pas encore atteint le statut \"Capturé\" peuvent être annulés. Dans le cas d'un paiement par carte de crédit, l'annulation de la transaction consiste à annuler l'autorisation.",
            self::AUTHORIZED => "Dans le cas d'un paiement par carte de crédit, les fonds sont \"retenus\" et déduits de la limite de crédit du client (ou de son solde bancaire, dans le cas d'une carte de débit), mais ne sont pas encore transférés au commerçant. Dans le cas des virements bancaires et de certains autres modes de paiement, le paiement passe immédiatement à l'état \"Capturé\" après avoir été défini comme \"Autorisé\".",
            self::CAPTURE_REQUESTED => "Une demande de capture a été envoyée à l'institution financière.",
            self::CAPTURED => "L'institution financière a traité le paiement. Les fonds seront transférés à HiPay Enterprise avant d'être réglés sur votre compte bancaire. Les paiements autorisés peuvent être capturés tant que l'autorisation n'a pas expiré. Certains modes de paiement, comme les virements bancaires ou les prélèvements automatiques, atteignent le statut \"Capturé\" immédiatement après avoir été autorisés.",
            self::PARTIALLY_CAPTURED => "Si seule une partie de la commande peut être expédiée, il est permis de capturer un montant égal à la partie expédiée de la commande. Remarque : comme l'exigent toutes les sociétés de cartes de crédit, il n'est pas permis à un commerçant de capturer un paiement avant que l'expédition ne soit terminée. Les commerçants doivent commencer à expédier la commande une fois que le statut \"Autorisé\" a été atteint !",
            self::REFUND_REQUESTED => "Une demande de remboursement a été envoyée à l'institution financière.",
            self::REFUNDED => "Un paiement atteint le statut \"Remboursé\" lorsque l'institution financière a traité le remboursement et que le montant a été transféré sur le compte du client. Le montant sera déduit du prochain montant total à payer au commerçant.",
            self::PARTIALLY_REFUNDED => "Le paiement a été partiellement remboursé.",
            self::CHARGED_BACK => "Le titulaire de la carte a annulé une saisie effectuée par sa banque ou la société émettrice de la carte de crédit. Par exemple, le titulaire de la carte a contacté la société émettrice de sa carte de crédit et a nié avoir effectué la transaction. La société de carte de crédit a alors annulé le paiement déjà saisi. Veuillez noter la différence juridique entre le client (qui a commandé les marchandises) et le titulaire de la carte (qui possède la carte de crédit et finit par payer la commande). Lorsqu'elles se produisent, le fait de contacter le client permet souvent de résoudre la situation. Dans certains cas, il s'agit d'une indication de fraude à la carte de crédit.",
            self::DISPUTE_LOST => "Le commerçant a perdu le litige relatif à la rétrofacturation. La rétrofacturation a déjà été appliquée à la transaction, elle s'applique toujours et ne sera donc pas remboursée.",
            self::AUTHORIZATION_REQUESTED => "La méthode de paiement utilisée nécessite une demande d'autorisation ; la demande a été envoyée et le système attend l'approbation de l'institution financière.",
            self::AUTHORIZATION_CANCELLED => "L'autorisation a été annulée",
            self::REFERENCE_RENDERED => "La référence de paiement à payer a été générée",
            self::REFUND_REFUSED => "L'opération de remboursement a été refusée par l'institution financière.",
            self::DEBITED => "La demande de crédit a été acceptée et le titulaire de la carte a été crédité du montant demandé.",
            self::CREDIT_REQUESTED => "Le commerçant a demandé à créditer directement le titulaire de la carte.",
            self::IN_PROGRESS => "Ce statut ne s'applique qu'aux transactions principales MixPayment. Toutes les sous-transactions de la transaction maître ne sont pas encore autorisées. Par conséquent, le montant total de la commande n'a pas encore été entièrement payé.",
            self::CAPTURE_REFUSED => "La capture a été refusée par l'institution financière.",
            self::AWAITING_TERMINAL => "La demande de transaction a été envoyée au terminal de paiement.",
            self::AUTHORIZATION_CONCELLATION_REQUESTED => "Le commerçant a demandé l'annulation de l'autorisation de la transaction.",
            self::CHALLENGE_REQUESTED => "La méthode de paiement utilisée nécessite une authentification ; la demande d'authentification a été envoyée et le système attend une action de la part du client.",
            self::SOFT_DECLINED => "L'autorisation a été refusée par l'émetteur car la transaction n'a pas été authentifiée. Vous pouvez réessayer l'autorisation après avoir authentifié le titulaire de la carte.",
            self::PENDING_PAYMENT => "The transaction request was submitted to the acquirer but the response is not yet available.",
            self::SUCCEEDED => 'Paiement validé',
        };
    }

    static public function code(int $code): self
    {
        return match($code)
        {
           109 => self::AUTHENTICATION_FAILED,
           110 => self::BLOCKED,
           111 => self::DENIED,
           112 => self::AUTHORIZED_AND_PENDING,
           113 => self::REFUSED,
           114 => self::EXPIRED,
           115 => self::CANCELLED,
           116 => self::AUTHORIZED,
           117 => self::CAPTURE_REQUESTED,
           118 => self::CAPTURED,
           119 => self::PARTIALLY_CAPTURED,
           124 => self::REFUND_REQUESTED,
           125 => self::REFUNDED,
           126 => self::PARTIALLY_REFUNDED,
           129 => self::CHARGED_BACK,
           134 => self::DISPUTE_LOST,
           142 => self::AUTHORIZATION_REQUESTED,
           143 => self::AUTHORIZATION_CANCELLED,
           144 => self::REFERENCE_RENDERED,
           165 => self::REFUND_REFUSED,
           166, 168 => self::DEBITED,
           169 => self::CREDIT_REQUESTED,
           172 => self::IN_PROGRESS,
           173 => self::CAPTURE_REFUSED,
           174 => self::AWAITING_TERMINAL,
           175 => self::AUTHORIZATION_CONCELLATION_REQUESTED,
           177 => self::CHALLENGE_REQUESTED,
           178 => self::SOFT_DECLINED,
           200 => self::PENDING_PAYMENT,
        };
    }
}
