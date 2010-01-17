import os, re
os.system('grep --exclude=*php~ --exclude=*tmp --exclude=*svn* -r ">run_hook" ../trunk/ > hooks.txt')

pat = re.compile(""".*['"](.*?)['"].*""")

print """
title: Hooks

This is a list of all the Hooks which are internally part of SnowCMS

"""

for line in open("hooks.txt"):
  linepart = line.replace("../trunk/","").split(":")
  m = pat.match(linepart[1])
  if m:
    lp = linepart[1].strip().split("//")
    print "Topic: ",m.group(1)
    if len(lp) > 1:
      print lp[1]
    print "(start code)"
    print lp[0]
    print "(end code)"
    print ""
