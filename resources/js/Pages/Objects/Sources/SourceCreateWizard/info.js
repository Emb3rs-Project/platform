const ESCRIPT = `endtime=starttime+hourperday;
fuelin=0 #gas consumption in (default 0 m3/h) [m3/h]
hcout=0; #supply heat/cold from equipment (0kW as default) [kW]
tempsource=15; #Heat pump/chiller/rankine cycle source temperature default [ºC]
temphcout=15; #supply heat temperature default [ºC]
temptargethcout=15; #target heat temperature default [ºC]
tempsupexhout=15; #default supply temperature of excess heat default [ºC]
temptargetexhout=15; #excess heat target temperature default [ºC]
mediumhcout="none" #supply heat medium default [-]
mediumexhout="none"; #excess heat medium default [-]
elecout=0; #electricity generated default [kW]
exhout=0; #excess heat available default [kW]
flowrateexhout=0; #excess heat flowtrate default [kg/h]
concen_ratio=0; #solar thermal concentration ratio default []
C0=0;C1=0;C2=0 #solar thermal performance parameters default
storagevol=0; #solar thermal storage water volume default [m3]

if equiptype=="steamboiler":
    fuelin=enegin*(-0.0068*enegin+112.02)/1000;
    conveff=0.88;
    hcout=enegin*conveff;
    temphcout=198;
    temptargethcout=80;
    tempsupexhout=145;
    mediumhcout="steam";
    mediumexhout="fluegas";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="condensingboiler":
    fuelin=enegin*(-0.0068*enegin+112.02)/1000;
    conveff=0.98;
    hcout=enegin*conveff;
    temphcout=80;
    temptargethcout=temphcout-30;
    tempsupexhout=65;
    mediumhcout="water";
    mediumexhout="fluegas";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="hotwaterboiler":
    fuelin=enegin*(-0.0068*enegin+112.02)/1000;
    conveff=0.92;
    hcout=enegin*conveff;
    temphcout=90;
    temptargethcout=temphcout-30;
    tempsupexhout=145;
    mediumhcout="water";
    mediumexhout="fluegas";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="airheater":
    fuelin=enegin*(-0.0068*enegin+112.02)/1000;
    conveff=0.925;
    hcout=enegin*conveff;
    temphcout=70;
    temptargethcout=temphcout-30;
    mediumhcout="air";
    mediumexhout="fluegas";
    tempsupexhout=70;
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="chillerair":
    conveff=3.3;
    hcout=elecin*conveff;
    temphcout=2;
    temptargethcout=7;
    tempsupexhout=45;
    mediumhcout="water";
    mediumexhout="air";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="chillerwater":
    conveff=3.5;
    hcout=elecin*conveff;
    temphcout=2;
    temptargethcout=7;
    tempsupexhout=45;
    mediumhcout="water";
    mediumexhout="water";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="thermalchillerair":
    conveff=1.5;
    tempsource=120;
    hcout=elecin*conveff;
    temphcout=7;
    temptargethcout=12;
    tempsupexhout=45;
    mediumhcout="water";
    mediumexhout="air";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="thermalchillerwater":
    conveff=24;
    tempsource=120;
    hcout=elecin*conveff;
    temphcout=7;
    temptargethcout=12;
    tempsupexhout=45;
    mediumhcout="water";
    mediumexhout="water";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="coolingtower":
    conveff=145;
    tempsource=38;
    hcout=enegin;
    temphcout=33;
    temptargethcout=38;
    tempsupexhout=38;
    temptargetexhout=33;
    mediumhcout="water";
    mediumexhout="water";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="gasengine":
    fuelin=enegin*(-0.0068*enegin+112.02)/1000;
    conveff=0.8406;
    elecout=enegin*conveff*(1-.525);
    hcout=enegin*conveff*.525;
    temphcout=90;
    temptargethcout=60;
    tempsupexhout=100;
    mediumhcout="water";
    mediumexhout="fluegas";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="gasturbine":
    fuelin=enegin*(-0.0068*enegin+112.02)/1000;
    conveff=0.649;
    elecout=enegin*conveff*(1-.634);
    hcout=enegin*conveff*.634;
    temphcout=190;
    temptargethcout=80;
    tempsupexhout=200;
    mediumhcout="steam";
    mediumexhout="fluegas";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="fuelcell":
    conveff=0.55;
    elecout=enegin*conveff;
    mediumexhout="oil";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="rankinecycle":
    conveff=0.4;
    elecout=enegin*conveff;
    tempsource=310;
    tempsupexhout=50;
    temptargetexhout=35;
    mediumexhout="water";
    exhout=enegin+elecin-hcout-elecout;
elif equiptype=="heatpump":
    conveff=2.5;
    hcout=elecin*conveff;
    temphcout=45;
    temptargethcout=35;
    tempsupexhout=2;
    mediumhcout="water";
    mediumexhout="air";
    exhout=hcout-elecin;
elif equiptype=="compressor":
    conveff=0.72;
    exhout=elecin*conveff;
    tempsupexhout=90;
    mediumexhout="oil";
elif equiptype=="flatplatecol":
    conveff="variable";
    hcout="variable";
    temphcout=60;
    temptargethcout=40;
    mediumhcout="water";
    concen_ratio=1;
    C0=.833; #colector optical performance []
    C1=4; #colector heat loss coefficient [W/m2.K]
    C2=.01; #colector heat loss coefficient 2 [W/m2.K2]
    storagevol=enegin*8/(1.145*40);
elif equiptype=="evactubecol":
    conveff="variable";
    hcout="variable";
    temphcout=90;
    temptargethcout=60;
    mediumhcout="water";
    concen_ratio=1;
    C0=.765; #colector optical performance []
    C1=1.26; #colector heat loss coefficient [W/m2.K]
    C2=.0052; #colector heat loss coefficient 2 [W/m2.K2]
    storagevol=enegin*8/(1.145*40);
elif equiptype=="cpccol":
    conveff="variable";
    hcout="variable";
    temphcout=90;
    temptargethcout=60;
    mediumhcout="water";
    concen_ratio=1,1;
    C0=.833; #colector optical performance []
    C1=3.3; #colector heat loss coefficient [W/m2.K]
    C2=.012; #colector heat loss coefficient 2 [W/m2.K2]
    storagevol=enegin*8/(1.145*40);
elif equiptype=="fresnelcol":
    conveff="variable";
    hcout="variable";
    temphcout=250;
    temptargethcout=220;
    mediumhcout="oil";
    concen_ratio=17;
    C0=.65; #colector optical performance []
    C1=1.26; #colector heat loss coefficient 1 [W/m2.K]
    C2=.0052; #colector heat loss coefficient 2 [W/m2.K2]
    storagevol=enegin*8/(1.145*40);

if tempsupexhout==temptargetexhout:
    mediumexhout=0
    flowrateexhout=0
else:
    #determining the flowrate of the excess heat stream [kg/h]
    if mediumexhout =="fluegas":
        mediumexhout=1;
        flowrateexhout=exhout/(1.005*(tempsupexhout-temptargetexhout))*3600;
    elif mediumexhout =="oil":
        mediumexhout=2;
        flowrateexhout=exhout/(2*(tempsupexhout-temptargetexhout))*3600;
    elif mediumexhout =="water":
        mediumexhout=3;
        flowrateexhout=exhout/(4.18*(tempsupexhout-temptargetexhout))*3600;
    else:
        mediumexhout=5;
        flowrateexhout=exhout/(1.5*(tempsupexhout-temptargetexhout))*3600;

#determining the flowrate of the supply heat/cold stream [kg/h]
if temphcout==temptargethcout:
    mediumhcout=0
    flowratehcout=0
else:
    if mediumhcout =="fluegas" or mediumhcout =="air":
        mediumhcout=1;
        flowratehcout=hcout/(1.005*(temphcout-temptargethcout))*3600;
    elif mediumhcout =="oil":
        mediumhcout=2;
        flowratehcout=hcout/(2*(temphcout-temptargethcout))*3600;
    elif mediumhcout =="water":
        mediumhcout=3;
        flowratehcout=hcout/(4.18*(temphcout-temptargethcout))*3600;
    elif mediumhcout =="steam":
        mediumhcout=4;
        flowratehcout=hcout/((0.00000017068*temphcout**3-0.000028553*temphcout**2+0.0047923*temphcout+1.6617)*(temphcout-temptargethcout))*3600;
    else:
        mediumhcout=5;
        flowratehcout=hcout/(1.5*(temphcout-temptargethcout))*3600;
#arrays required
hgen=np.zeros(8760)
day=np.zeros(8760);
hday=np.zeros(8760);
week=np.zeros(8760);
weekday=np.zeros(8760);

for i in range (8760):
    if i==0:
        day[i]=0
        hday[i]=i-day[i]*24
    else:
        day[i]=round((i-11.9)/24)
        hday[i]=i-day[i]*24

    if hday[i]<starttime or hday[i]>endtime-1:
        hgen[i]=0;
    else:
        hgen[i]=1*plant[i];

val=np.zeros(8769)

val[0]=mediumhcout; #supply heat/cold medium code (1-fluegas,2-oil,3-water,4-steam, 5-other)
val[1]=flowratehcout; #flowrate supply heat/cold medium
val[2]=temphcout; #supply temperature supply heat/cold
val[3]=temptargethcout; #return temperature supply heat/cold

val[4]=mediumexhout; #excess heat medium code (1-fluegas,2-oil,3-water,4-steam, 5-other) - PLATTFORM
val[5]=exhout; #excess heat generation (kW) - PLATTFORM
val[6]=flowrateexhout; #flowrate excess heat medium (m3/h) - PLATTFORM
val[7]=tempsupexhout; #supply temperature excess heat (ºC) - PLATTFORM
val[8]=temptargetexhout; #return temperature excess heat (15degC by default) - PLATTFORM

val[9:8769]=hgen[:];#hourly generation profile throughout the year (kWh/h)

`;


export default ESCRIPT;
