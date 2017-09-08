<?
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
//session_destroy();
?>

<!DOCTYPE HTML>

<html>

    <head>
    
        <meta charset="utf-8">
        
    	<title>DDP application - are you a social capitalist?</title>
        
    	<meta name="title" content="DDP application - are you a social capitalist?" />
    	<meta name="description" content="Find out the real influence of a Twitter user based on his activity and the way he obtains it." /> 
    	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    	<link rel="image_src" href="/images/notify_better_image.png" />
    
    	<meta content="http://www.bit.ly/DDPapp" property="og:url" />
    	
    	<meta name="author" content="Maximilien Danisch, Nicolas Dugué, Anthony Perez">
    	
        <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">
    
        <link rel="stylesheet" type="text/css" href="ddp.css" />
        <link rel="stylesheet" type="text/css" href="hover.css" />
        <link rel="stylesheet" type="text/css" href="media.css" />
        <link rel="stylesheet" type="text/css" href="onepage-scroll.css" />
       
    	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="jquery.onepage-scroll.js"></script>
        
        <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css' />
	<link href="http://fonts.googleapis.com/css?family=Arvo:400,700" rel="stylesheet" type="text/css" />
        
    	<script>
    	  $(document).ready(function(){
          $(".main").onepage_scroll({
            sectionContainer: "section",
            responsiveFallback: 600,
            loop: true
          });
    		});
    		
    	</script>
    </head>

    <body>
    
        <div class="wrapper">

            <div class="main">

                <section class="page1">
                   	
                    <div id="socialcapitalism">
                    
			<h1 class="home">Social capitalism</h1>

                        <p>Some Twitter users are known to use social capitalism techniques to gain followers. Follow them, they will follow you. Promess to follow back, they will follow you. They even use specific hashtags and retweet techniques to find each other. Interact with them, you will gain followers, retweets and mentions.</p><br />
                        
                        <h2>
                            <span>But does that make you influent?</span>
                        </h2>
                        
                        <div style="clear: both;"></div><br />
                        
                    </div>
                    
                    <div id="image_socialcapitalism">

			<h1 class="home">Are you a social capitalist?</h1>
                    
                    	<div id="giveitatry">
            		
            			<? if ($_SESSION['status'] == "verified")  { ?>
            			
		                                <p>Give the screen name of a Twitter user to check whether he is a social capitalist: <br /></p>
            		
            				<p id="connected">&nbsp;
				    		<form action="results/index.php" method="post" target="_blank" style="text-align: center;">
				    			<input type="text" size="40" maxsize="40" value="barackobama" name="username">
				    			<input type="submit" value="Try it!">
				    		
				    		</form>
				    	</p>
		                                
				<? } else { ?>
				
					 <p>To test whether a user is a social capitalist, you must sign in with your Twitter account to allow our application to obtain all needed parameters:</p>
					 
					 <div style="clear: both;"></div><br />
					 
					<p id="connected">
						<a href="./redirect.php"><img src="./images/lighter.png" alt="Sign in with Twitter"/></a>
					</p>

				<? } ?>		 
		            	
                    	</div>
                    
                    </div>
                    
                    <div style="clear: both;"></div><br />
                
                    <div id="influence">
                    
                        <h1 class="home">Influence on Twitter</h1>
                        
                        <p>Current tools measuring influence on Twitter (<a href="http://www.klout.com" target="_blank">Klout</a>, <a href="http://www.kred.com" target="_blank">Kred</a>) <b>do not</b> consider how interactions and followers are obtained. Some obvious social capitalists (and even automatic accounts) are considered as highly influent. </p><br />
                        
                    </div>
                    
                    <div id="image_influence">
                    
                        <div class="cadre">
                        	<div class="view view-fifth">
		                    <img src="images/barackobama.png" />
		                    <div class="mask">
				        <h2>@BarackObama</h2>
				        <div style="clear: both;"></div>
				        <p>Barack Obama's Twitter account is considered as one of the most influent on the network.</p>
				        <!--<a href="#" class="info">Read More</a>-->
				    </div>
				</div>
				<div class="score">
					<img src="images/klout.png" /> 99<br />
					<img src="images/kred.png" /> 100
				</div>
                    	</div>
                    
                        <div class="cadre">
                        	<div class="view view-fifth">
		                    <img src="images/oprahwinfrey.jpg" />
		                    <div class="mask">
				        <h2>@Oprah</h2>
				        <div style="clear: both;"></div>
				        <p>Oprah Winfrey is a popular american talk show host. She has a very active Twitter account, with more than 20 millions followers. </p>
				    </div>
				</div>
				<div class="score">
					<img src="images/klout.png" /> 93 <br />
					<img src="images/kred.png" /> 100
				</div>
                    	</div>
                    
                        <div class="cadre">
                        	<div class="view view-fifth">
		                    <img src="images/thecat.jpg" />
		                    <div class="mask">
				        <h2>@GB_FollowBack</h2>
				        <div style="clear: both;"></div>
				        <p>The Cat is an obvious social capitalist that does not tweet any relevant content. He yet manages to achieve high influential scores. </p>
				    </div>
				</div>
				<div class="score">
					<img src="images/klout.png" /> 70 <br />
					<img src="images/kred.png" /> 100
				</div>
                    	</div>
                    
                        <div class="cadre">
                        	<div class="view view-fifth">
		                    <img src="images/zombie.gif" />
		                    <div class="mask">
				        <h2>@z_o_m_b_ii_e</h2>
				        <div style="clear: both;"></div>
				        <p>#TeamFollowBack already got almost a half billions of followers and achieves high influence scores despite his unrelevant tweets.</p>
				    </div>
				</div>
				<div class="score">
					<img src="images/klout.png" /> 62 <br />
					<img src="images/kred.png" /> 98
				</div>
                    	</div>
                    
                    </div>
                    
                    <div style="clear: both;"></div>
                    
                    <div id="ksoc">
                        We estimate the probability of being a social capitalist and adjust influence score accordingly. 
                    </div>
                        
                </section>

                <section class="page2">
                
                    <div id="dataset">
                    
		            <h1 class="home">Features</h1>
		            
		            <code class="html">
		                We retrieve several features for a given Twitter user, namely: <br /><br />
		                <div id="features">
		                
		                	number of followers, friends, listed and favorites <br />
		                	average tweet length<br> 
		                	number of hashtag/url/mentions per tweet<br />
		                	average time between two tweets<br />
		                	sources used to send the tweets
		                
		                </div>
		                
		                <div style="clear: both;"></div><br />
		                
		                Due to Twitter's API restrictions, these features are computed over the last 200 tweets of the given user. 
		                <!--These features allow us to detect the probability for a user to be a social capitalist with a very effective accuracy. -->
		            </code>
		            
		    </div>
		    
		    <div id="image_dataset">
		    
		    	<img src="images/results.png" />
		    
		    </div>
		    
		    <div style="clear: both;"></div>
		    
		    <div id="classifier">
		    
		    	<h1 class="home">The algorithm</h1>
		    
		    	<code class="js">
			    	We use all above features to find the probability P<sub>KSoc</sub> for a user to be a social capitalist. Our algorithm is based on <a href="http://en.wikipedia.org/wiki/Logistic_regression">Logistic regression</a>, a classical Machine Learning tool for classification. 
			    	We then use this probability to balance Klout's score: <br><br>
			    	
			    	<div style="text-align: center;">
			    	
			    		S<sub>DDP</sub> = 2 * (1 - P<sub>KSoc</sub>) * S<sub>Klout</sub> if P<sub>KSoc</sub> >= 0.5 <br>
			    		S<sub>DDP</sub> = S<sub>Klout</sub> otherwise
			    	
			    	</div>
			    	
			    	<br>We will carry on improving the accuracy of the algorithm in the next couple of months.
			    	
			</code>
		    
		    </div>
		    
		    <div id="image_classifier">
		    
		    	<img src="images/logreg.png" />
		    
		    </div>
                    
                    
                </section>

                <section class="page3">
                
                	<div id="about">
                
		        	<div id="theproject">
		            
				<h1 class="home">The project</h1>
				        
				        <p>In 2012, Ghosh et al. conducted a study about spammers on Twitter, and observed that users that respond the most to solicitations of spammers are mostly real, active users. This observation can be easily explained: a lot of Twitter users are trying to gain influence on the network by getting as many followers as possible. In particular, they cheat with the friend-follower reciprocation. <br /><br />
				        
				        In a recent work, Nicolas and Anthony provided a simple -yet effective- algorithm to detect such users. They enlighted that some of these users have a very high number of followers. Based on this observation they started considering the impact of social capitalism on the notion of influence on Twitter. <br /><br />
				        
				        Maximilien naturally joined the project since he was already working on the matter with Nicolas. His knowledge on machine learning algorithms allowed us to create an efficient classifier that can discriminates social capitalists from real, truthful users with a high probability. We thus use this probability to ponderate the influential score of a given user. We chose to use Klout because it is the most spread influential score. We simply use the probability for a user to be a social capitalist to ponderate its Klout score. <br /><br />
				        
				        This is the first step of the project. Our final aim is to provide a <b>new way to measure influence on Twitter</b>, with a totally free and open source code. <!--We are currently working on this matter: using the several features we retrieve with a notion of temporality (some actual tools can consider inactive users as influence), we are developping a new influential score. We hope this will help users to better understand the network they are evolving on. --></p>
				        
				</div>
				
				<div id="references">
				
					<h1 class="home">References</h1>
					
					<ol style="clear:both;">
					
						<li>
						
							Ghosh S., Viswanath B., Kooti F., Sharma N.K., Korlam G., Benevenuto F., Ganguly N., Gummadi K.P. <br /> 
							<a href="http://dl.acm.org/citation.cfm?id=2187846" target="_blank">Understanding and combating link farming in the twitter social network</a>. <br />
							Proceedings of the 21st international conference on <a href="http://www.informatik.uni-trier.de/~ley/db/conf/www/www2012.html" target="_blank">World Wide Web, WWW</a> - 2012.
							
					
						</li>
						
						<li>
						
							Dugué N. and Perez A. <br />
							<a href="http://link.springer.com/article/10.1007%2Fs13278-014-0178-4" target="_blank">Social capitalists on Twitter: detection, evolution and behavioral analysis</a>. <br />
							<a href="http://www.springer.com/computer/database+management+%26+information+retrieval/journal/13278" target="_blank">Social Network Analysis and Mining</a> - 2014.
						
						</li>
					
					</ol>
				
				</div>
				
			</div>
			
			<div id="theteam">
			
				<div class="cadre sep" id="maximilien">
				
					<img src="images/maximiliendanisch.jpg" />
					
					<p><a href="http://perso.crans.org/danisch/max/home.html" target="_blank">Maximilien Danisch</a> is a Ph.D. candidate at <a href="http://www.lip6.fr" target="blank">LIP6</a> (Université Pierre et Marie Curie, Paris, France) under the supervision of <a href="" target="_blank">Jean-Loup Guillaume</a> and <a href="" target="_blank">Bénédicte Le Grand</a>. He mainly works on community detection, and has a strong background in Machine Learning. The method he chose allows us to obtain a probability for a given user to be a social capitalist, which in turn allows us to balance Klout's score. </p>
				
				</div>
				
				<div style="clear: top;"></div>
				
				<div class="cadre sep" id="nicolas">
				
					<img src="images/nicolasdugue.jpg" />
					
					<p><a href="http://www.univ-orleans.fr/lifo/membres/Nicolas.Dugue/" target="_blank">Nicolas Dugué</a> is a Ph.D. candidate at <a href="http://www.univ-orleans.fr/lifo" target="_blank">LIFO</a> (Université d'Orléans, France) under the supervision of <a href="http://www.univ-orleans.fr/lifo/membres/Anthony.Perez/" target="_blank">Anthony Perez</a>. His research mainly concerns the impact of social capitalism on the Twitter network. In a joint work with Anthony, he provided a method that allows to detect social capitalists by considering the network topology only. He also created an automatic account (<i>@rain_bow_ash</i>) that uses social capitalism techniques and gained quickly a high number of followers. 
				
				</div>
				
				<div style="clear: top;"></div>
				
				<div class="cadre" id="anthony">
				
					<img src="images/perezanthony.jpg" />
					
					<p><a href="http://www.univ-orleans.fr/lifo/membres/Anthony.Perez/" target="_blank">Anthony Perez</a> is an associate professor at <a href="http://www.univ-orleans.fr/lifo" target="_blank">LIFO</a> (Université d'Orléans, France) since 2012. His research mainly concerts so-called parameterized complexity, kernelization algorithms and graph theory. His work with Nicolas Dugué recently lead him to consider large data and social networks analysis and mining as well.  
				
				</div>
			
			</div>
                
                </section>
                
            </div>
                
        </div>
         
        <!-- AddThis Smart Layers BEGIN -->
	<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5346691604bfaa54"></script>
	<script type="text/javascript">
	  if(screen.width > 1180) {
	addthis.layers({
	    'theme' : 'transparent',
	    'share' : {
	      'position' : 'left',
	      'numPreferredServices' : 4,
	      'services' : 'facebook,twitter,email,,more',
	    }   
	  });}
	</script>
	<!-- AddThis Smart Layers END -->
      
    </body>

</html>
