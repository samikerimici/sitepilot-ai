<?php
namespace SitePilotAI\Services;
if ( ! defined( 'ABSPATH' ) ) { exit; }
final class Assistant {
    public function answer($message,$settings,$scan=array()) {
        $message=trim(wp_strip_all_tags($message));
        if (!$message) return array('agent'=>'SitePilot','answer'=>'Écrivez votre demande pour commencer.');
        if (!empty($settings['api_key']) && 'openai' === ($settings['provider'] ?? 'none')) {
            $remote=$this->openai($message,$settings,$scan);
            if (!is_wp_error($remote)) return array('agent'=>$this->detect_agent($message),'answer'=>$remote);
        }
        $agent=$this->detect_agent($message);
        $business=$settings['business_name'] ?: get_bloginfo('name');
        $city=$settings['city'] ?: 'votre zone';
        $low=remove_accents(strtolower($message));
        if (strpos($low,'actualit')!==false || strpos($low,'article')!==false) {
            return array('agent'=>'Rédacteur','answer'=>"Je peux préparer une actualité pour {$business}. Indiquez-moi le sujet précis, l’offre ou l’événement à mettre en avant, ainsi que la date éventuelle. Je l’adapterai à {$city} et au ton de votre entreprise.");
        }
        if (strpos($low,'seo')!==false || strpos($low,'referenc')!==false || strpos($low,'google')!==false) {
            $score=isset($scan['scores']['seo'])?(int)$scan['scores']['seo']:null;
            return array('agent'=>'Expert SEO','answer'=>($score?"Votre score SEO actuel est de {$score}/100. ":'')."Je vous recommande de commencer par les descriptions manquantes, les contenus trop courts et les images sans texte ALT. Lancez la mission « Développer ma visibilité » pour obtenir la liste priorisée.");
        }
        if (strpos($low,'lent')!==false || strpos($low,'performance')!==false || strpos($low,'vitesse')!==false) return array('agent'=>'Expert Performance','answer'=>'Je peux contrôler les mises à jour, les images et les signaux techniques visibles depuis WordPress. Pour mesurer précisément les Core Web Vitals, une connexion à un service de mesure externe sera ajoutée dans une prochaine version.');
        if (strpos($low,'secur')!==false || strpos($low,'pirat')!==false) return array('agent'=>'Expert Sécurité','answer'=>'Je vais prioriser HTTPS, les mises à jour WordPress et extensions, ainsi que le nombre de comptes administrateurs. Lancez une nouvelle analyse pour actualiser le diagnostic.');
        return array('agent'=>$agent,'answer'=>"J’ai compris votre demande pour {$business}. Cette version de test peut analyser le site, prioriser les actions et vous guider. Pour une génération complète et personnalisée, configurez une clé OpenAI dans Réglages, puis reformulez votre demande avec l’objectif, le public visé et le ton souhaité.");
    }
    private function detect_agent($m){$l=remove_accents(strtolower($m)); if(preg_match('/seo|referenc|google/',$l))return'Expert SEO';if(preg_match('/article|actualit|faq|texte|contenu/',$l))return'Rédacteur';if(preg_match('/secur|pirat|ssl/',$l))return'Expert Sécurité';if(preg_match('/lent|vitesse|performance/',$l))return'Expert Performance';if(preg_match('/facebook|linkedin|campagne|marketing/',$l))return'Expert Marketing';return'SitePilot';}
    private function openai($message,$s,$scan){
        $system='Tu es SitePilot AI, copilote WordPress de Lorraine Digital. Réponds en français, clairement, avec des actions concrètes et sans jargon. Entreprise: '.($s['business_name']??'').'. Activité: '.($s['business_type']??'').'. Ville: '.($s['city']??'').'. Ton: '.($s['tone']??'professional').'. Scan: '.wp_json_encode($scan).'. Ne prétends jamais avoir effectué une action non réalisée.';
        $res=wp_remote_post('https://api.openai.com/v1/chat/completions',array('timeout'=>45,'headers'=>array('Authorization'=>'Bearer '.$s['api_key'],'Content-Type'=>'application/json'),'body'=>wp_json_encode(array('model'=>!empty($s['model'])?$s['model']:'gpt-4.1-mini','temperature'=>0.5,'messages'=>array(array('role'=>'system','content'=>$system),array('role'=>'user','content'=>$message))))));
        if(is_wp_error($res))return $res; $code=wp_remote_retrieve_response_code($res); $body=json_decode(wp_remote_retrieve_body($res),true); if($code<200||$code>=300||empty($body['choices'][0]['message']['content']))return new \WP_Error('spai_api','Réponse API invalide'); return sanitize_textarea_field($body['choices'][0]['message']['content']);
    }
}
