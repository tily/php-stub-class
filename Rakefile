
desc 'unit test'
task :default => [:test]

desc 'unit test'
task :test do
  sh 'phpunit --testdox ./test/'
end

desc 'output code coverage'
task :coverage do
  sh 'phpunit --coverage-html ./test/coverage/ ./test/'
end

desc 'output document'
task :doc do
  sh 'phpdoc -t ./doc/ -d ./lib/PHPStubClass -o HTML:Smarty:PHP'
end

desc 'build package'
task :build do 
  sh 'php ./build/package.php make'
  sh 'pear package ./package.xml'
  sh 'mv ./package.xml ./PHPStubClass-0.0.1.tgz ./build/'
end

