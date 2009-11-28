import sys
#    returns ,\n    Returns: \n      
name = sys.argv[1]
f = open("core/"+name)
com = False
clone = ""
idt = 0
iidt = 0
ncmt = ""
typ = ""
nam = ""
for line in f:
  if line.strip() == "/*":
    print "start comment"
    com = True
    ncmt = ""
    clone += line
  elif line.strip() == "*/":
    print "end comment"
    com = False
    
    clone += (" "*iidt)+"  "+typ+": "+nam+"\n"
    clone += ncmt
    clone += line
  elif com == True:
    if len(ncmt) == 0:
      iidt = idt = len(line) - len(line.lstrip())
    elif idt > len(line) - len(line.lstrip()):
      print "<-",line
      idt = len(line) - len(line.lstrip())
    elif idt < len(line) - len(line.lstrip()):
      print "->",line
      idt = len(line) - len(line.lstrip())
    if "@" not in line:
      ncmt += line.replace("    returns ","\n    Returns: \n     ")
    else:
      ncmt += (" "*idt)+"Parameters:\n"
      par = line.strip().replace("@","").replace("("," ").split(" ")
      typ = par[0].capitalize()
      nam = par[3]
    #yay parsing:
  else:
    clone += line
f.close()
nf = open("core/"+name,"w")
nf.write(clone)
