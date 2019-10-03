# quizee
This is a quizzing site.

## How to set up

1. git clone https://github.com/subham103/quizee.git
2. cd quizee
3. Change the paths in configs/mvc.sample.local.conf pointing to the public folder of this project
4. sudo cp ~/configs/mvc.sample.local.conf /etc/apache2/sites-available/
5. Add mvc.sdslabs.local entry to your /etc/hosts
6. sudo a2ensite mvc.sample.local.conf
7. sudo service apache2 restart
8. Open http://mvc.sample.local in your browser
