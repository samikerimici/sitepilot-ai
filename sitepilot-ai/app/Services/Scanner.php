<?php
namespace SitePilotAI\Services;
if ( ! defined( 'ABSPATH' ) ) { exit; }
final class Scanner {
    public function run() {
        global $wpdb;
        $posts = (int) wp_count_posts( 'post' )->publish;
        $pages = (int) wp_count_posts( 'page' )->publish;
        $images = (int) $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_type='attachment' AND post_mime_type LIKE 'image/%'" );
        $missing_alt = (int) $wpdb->get_var( "SELECT COUNT(p.ID) FROM {$wpdb->posts} p LEFT JOIN {$wpdb->postmeta} pm ON p.ID=pm.post_id AND pm.meta_key='_wp_attachment_image_alt' WHERE p.post_type='attachment' AND p.post_mime_type LIKE 'image/%' AND (pm.meta_value IS NULL OR pm.meta_value='')" );
        $thin = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_status='publish' AND post_type IN ('post','page') AND CHAR_LENGTH(post_content) < %d", 350 ) );
        $missing_title = (int) $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_status='publish' AND post_type IN ('post','page') AND post_title=''" );
        $no_h1 = 0; $no_meta = 0;
        $content_ids = get_posts(array('post_type'=>array('post','page'),'post_status'=>'publish','posts_per_page'=>100,'fields'=>'ids'));
        foreach ($content_ids as $id) {
            $content = (string) get_post_field('post_content',$id);
            if ( false === stripos($content,'<h1') ) { $no_h1++; }
            $yoast = get_post_meta($id,'_yoast_wpseo_metadesc',true);
            $rank = get_post_meta($id,'rank_math_description',true);
            if ( empty($yoast) && empty($rank) && ! has_excerpt($id) ) { $no_meta++; }
        }
        $updates = get_site_transient('update_plugins');
        $plugin_updates = is_object($updates) && isset($updates->response) ? count($updates->response) : 0;
        $core = get_site_transient('update_core');
        $core_update = 0;
        if ( is_object($core) && !empty($core->updates) && isset($core->updates[0]->response) && 'upgrade' === $core->updates[0]->response ) { $core_update = 1; }
        $ssl = is_ssl() ? 1 : 0;
        $admin_count = count(get_users(array('role'=>'administrator','fields'=>'ID')));
        $last = get_posts(array('post_type'=>'post','post_status'=>'publish','posts_per_page'=>1,'orderby'=>'date','order'=>'DESC','fields'=>'ids'));
        $days = $last ? max(0,(int)floor((time()-get_post_time('U',true,$last[0]))/DAY_IN_SECONDS)) : null;
        $scores = array(
            'seo' => max(20,100-min(80,($missing_alt*2)+($thin*5)+($no_meta*3)+($missing_title*10))),
            'content' => max(25,100-min(75,($thin*8)+(null===$days?25:($days>60?20:($days>30?10:0))))),
            'images' => $images ? max(20,100-(int)round(($missing_alt/$images)*100)) : 100,
            'security' => max(30,100-($ssl?0:35)-($core_update*20)-($plugin_updates*4)-($admin_count>3?10:0)),
            'updates' => max(20,100-($core_update*35)-min(45,$plugin_updates*6)),
        );
        $score = (int) round($scores['seo']*.30+$scores['content']*.20+$scores['images']*.15+$scores['security']*.25+$scores['updates']*.10);
        $recommendations = array();
        if ($core_update) $recommendations[]=$this->rec('Élevée','Sécurité','Mettre WordPress à jour','Une mise à jour du cœur est disponible.',10,'update-core.php');
        if ($plugin_updates) $recommendations[]=$this->rec('Élevée','Sécurité',sprintf('Mettre à jour %d extension(s)',$plugin_updates),'Réduisez les risques et conservez la compatibilité.',8,'plugins.php?plugin_status=upgrade');
        if (!$ssl) $recommendations[]=$this->rec('Élevée','Sécurité','Activer HTTPS','Le site ne semble pas utiliser une connexion sécurisée.',15,'options-general.php');
        if ($missing_alt) $recommendations[]=$this->rec('Moyenne','Images',sprintf('Optimiser %d image(s) sans texte ALT',$missing_alt),'Améliorez accessibilité et compréhension par Google.',5,'admin.php?page=sitepilot-ai-images');
        if ($no_meta) $recommendations[]=$this->rec('Moyenne','SEO',sprintf('Compléter %d description(s) SEO',$no_meta),'Des extraits plus clairs peuvent améliorer les clics.',8,'admin.php?page=sitepilot-ai-seo');
        if ($thin) $recommendations[]=$this->rec('Moyenne','Contenu',sprintf('Enrichir %d contenu(s) court(s)',$thin),'Apportez plus de valeur aux visiteurs.',12,'edit.php');
        if (null===$days || $days>45) $recommendations[]=$this->rec('Faible','Contenu',null===$days?'Publier votre première actualité':sprintf('Publier une actualité — %d jours d’inactivité',$days),'Montrez que votre entreprise est active.',10,'admin.php?page=sitepilot-ai-content');
        if (!$recommendations) $recommendations[]=$this->rec('Faible','Site','Maintenir le bon niveau actuel','Aucune priorité urgente détectée.',2,'admin.php?page=sitepilot-ai-assistant');
        $data=compact('posts','pages','images','missing_alt','thin','missing_title','no_h1','no_meta','plugin_updates','core_update','ssl','admin_count','days','scores','score','recommendations');
        $data['scanned_at']=current_time('mysql');
        update_option('spai_last_scan',$data,false);
        $history=get_option('spai_scan_history',array());
        array_unshift($history,array('scanned_at'=>$data['scanned_at'],'score'=>$score,'scores'=>$scores,'recommendations'=>count($recommendations)));
        update_option('spai_scan_history',array_slice($history,0,30),false);
        return $data;
    }
    private function rec($priority,$category,$title,$description,$minutes,$url){return compact('priority','category','title','description','minutes','url');}
}
