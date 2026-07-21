<?php
namespace SitePilotAI\Services;
if ( ! defined( 'ABSPATH' ) ) { exit; }
final class ContentGenerator {
 public function generate($request,$settings){
  $type=sanitize_key($request['type']??'news');
  $subject=sanitize_text_field($request['subject']??'');
  $objective=sanitize_text_field($request['objective']??'Informer les visiteurs');
  $city=sanitize_text_field($request['city']??'');
  $tone=sanitize_text_field($request['tone']??'professionnel');
  $keywords=sanitize_text_field($request['keywords']??'');
  $length=sanitize_key($request['length']??'medium');
  if(!$subject)return new \WP_Error('spai_subject','Le sujet est obligatoire.');
  if(!empty($settings['api_key']) && 'openai'===($settings['provider']??'none')){
   $ai=$this->openai(compact('type','subject','objective','city','tone','keywords','length'),$settings);
   if(!is_wp_error($ai))return $ai;
  }
  return $this->local(compact('type','subject','objective','city','tone','keywords','length'),$settings);
 }
 private function local($r,$s){
  $business=$s['business_name']?:get_bloginfo('name');
  $place=$r['city']?' à '.$r['city']:'';
  $title=$r['subject'];
  $intro='<p><strong>'.$this->e($business).'</strong> vous présente '.$this->e(lcfirst($r['subject'])).$this->e($place).'. Cette publication a pour objectif de '.$this->e(lcfirst($r['objective'])).'.</p>';
  $body=$intro.'<h2>Ce qu’il faut retenir</h2><p>Présentez ici les informations essentielles, les bénéfices pour vos clients et les éventuelles dates ou conditions à connaître.</p><h2>Pourquoi est-ce important ?</h2><p>Expliquez concrètement ce que cette nouveauté, ce service ou cette actualité apporte à votre public.</p><h2>Besoin d’en savoir plus ?</h2><p>Contactez '.$this->e($business).' pour obtenir davantage d’informations ou être accompagné.</p>';
  if('faq'===$r['type']){$title='FAQ — '.$r['subject'];$body='<h2>Questions fréquentes</h2>';for($i=1;$i<=6;$i++)$body.='<h3>Question '.$i.' sur '.$this->e($r['subject']).' ?</h3><p>Ajoutez ici une réponse claire, courte et utile pour vos visiteurs.</p>';}
  elseif('page'===$r['type']){$body=$intro.'<h2>Une solution adaptée à vos besoins</h2><p>Décrivez ici votre offre, votre méthode et les résultats attendus.</p><h2>Nos points forts</h2><ul><li>Un accompagnement personnalisé</li><li>Une réponse claire et professionnelle</li><li>Une expertise adaptée à votre activité</li></ul><h2>Parlons de votre projet</h2><p>Contactez '.$this->e($business).' pour échanger sur vos besoins.</p>';}
  elseif('article'===$r['type']){$body=$intro.'<h2>Comprendre le sujet</h2><p>Développez le contexte et répondez à la principale question de vos lecteurs.</p><h2>Les bonnes pratiques</h2><p>Présentez plusieurs conseils concrets, illustrés par des exemples liés à votre activité.</p><h2>À retenir</h2><p>Résumez les points clés et invitez vos lecteurs à passer à l’action.</p>';}
  $excerpt=wp_trim_words(wp_strip_all_tags($intro),28,'…');
  $meta=mb_substr($excerpt,0,155);
  return array('title'=>$title,'content'=>$body,'excerpt'=>$excerpt,'meta_description'=>$meta,'source'=>'local');
 }
 private function openai($r,$s){
  $lengths=array('short'=>'500 mots','medium'=>'800 mots','long'=>'1200 mots');
  $system='Tu es le rédacteur de SitePilot AI. Rédige en français pour une PME. Retourne uniquement un JSON valide avec les clés title, content, excerpt, meta_description. content doit être du HTML WordPress simple (p,h2,h3,ul,li,strong), sans markdown ni code fence. Ne fabrique pas de chiffres, dates, témoignages ou promesses.';
  $prompt='Entreprise: '.($s['business_name']??'')."\nActivité: ".($s['business_type']??'')."\nServices: ".($s['services']??'')."\nVille: ".$r['city']."\nZone: ".($s['area']??'')."\nType: ".$r['type']."\nSujet: ".$r['subject']."\nObjectif: ".$r['objective']."\nTon: ".$r['tone']."\nMots-clés: ".$r['keywords']."\nLongueur cible: ".($lengths[$r['length']]??'800 mots');
  $res=wp_remote_post('https://api.openai.com/v1/chat/completions',array('timeout'=>60,'headers'=>array('Authorization'=>'Bearer '.$s['api_key'],'Content-Type'=>'application/json'),'body'=>wp_json_encode(array('model'=>!empty($s['model'])?$s['model']:'gpt-4.1-mini','temperature'=>0.55,'response_format'=>array('type'=>'json_object'),'messages'=>array(array('role'=>'system','content'=>$system),array('role'=>'user','content'=>$prompt))))));
  if(is_wp_error($res))return $res;$code=wp_remote_retrieve_response_code($res);$body=json_decode(wp_remote_retrieve_body($res),true);$raw=$body['choices'][0]['message']['content']??'';if($code<200||$code>=300||!$raw)return new \WP_Error('spai_api','Réponse API invalide.');$data=json_decode($raw,true);if(!is_array($data)||empty($data['title'])||empty($data['content']))return new \WP_Error('spai_json','Contenu IA incomplet.');
  return array('title'=>sanitize_text_field($data['title']),'content'=>wp_kses_post($data['content']),'excerpt'=>sanitize_textarea_field($data['excerpt']??''),'meta_description'=>sanitize_text_field($data['meta_description']??''),'source'=>'openai');
 }
 private function e($v){return esc_html($v);}
}
