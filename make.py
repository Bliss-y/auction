import sys

if "controller" == sys.argv[1]:
    print(sys.argv)
    content="<?php\n namespace http\Controllers;\n use Framework\DatabaseTable;\n class " +sys.argv[2] +" { public function __construct()\n { }\npublic function index(){}\n\n }";
    f = open("./websites/default/http/Controllers/" + sys.argv[2] + ".php","w");
    f.write(content);
    f.close()

if "view" == sys.argv[1]:
    print(sys.argv)
    f = open("./websites/default/views/" + sys.argv[2] + ".html.php","w");
    if len(sys.argv) > 3:
        f.write(f"<h2>{sys.argv[3]}<h2>")
    f.write(content);
    f.close()