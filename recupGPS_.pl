#!/usr/bin/perl -w

use LWP::UserAgent;
use HTTP::Cookies;
use File::Copy;
use HTTP::Headers;
use Data::Dumper;
use JSON;
use lib 'D:\Thermcross\Projets\libPerl';
use th_bdd;

my $societe=110;

my $ua = new LWP::UserAgent(keep_alive => 1);
$ua->agent('Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36');
$ua->timeout(30);

my $sqlClient="select codsoc,sigtie from tie where codsoc=$societe and typtie='CLI' and codett='ACT'";

my $dbh=connexionBDD('EGX_TEST');
my $sth = $dbh->prepare($sqlClient);
$sth->execute() or print "Erreur...\n";
$sth->bind_columns(\$codsoc,\$sigtie);
while($sth->fetch){
	my $ligneAdr=getAdresse($codsoc,$sigtie);
	my ($gpsX,$gpsY)=getGPS($ligneAdr);
	updateGPS($codsoc,$sigtie,$gpsX,$gpsY);
	attente(2,5);
	# last;
}
$sth->finish;
$dbh->disconnect();

###############################
## SUBROUTINES
###############################
sub getAdresse {
	my ($codsoc,$sigtie) = @_;
	
	print "Traitement $codsoc-$sigtie : ";
	
	my $sqlAdresse="select adress,codpos,cenpos from adr where codsoc=$codsoc and sigadr='$sigtie' and typadr='COM' and numadr=1";
	# print "$sqlAdresse\n";
	my $sth2 = $dbh->prepare($sqlAdresse);
	$sth2->execute() or die "Erreur execute ...\n";
	my ($adress,$codpos,$cenpos)=$sth2->fetchrow_array();
	$sth2->finish();
	my $ligneAdresse=$adress.', '.$codpos.' '.$cenpos;
	# print $codsoc."-".$sigtie." ==> ".$ligneAdresse."\n";
	return $ligneAdresse;
}

sub getGPS {
	my ($adresse)=@_;

	my $url="https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates?outSr=4326&forStorage=false&outFields=*&maxLocations=20&singleLine=$adresse&f=json";
	
	$response = $ua->get($url);
	if ( $response->code == 200 ) {
		my $hashResponse = decode_json($response->decoded_content());
		my %hash = %{$hashResponse};
		# print Dumper(\%hash);
		
		my @lstCandidates=@{$hash{'candidates'}};
		my %hash2=%{$lstCandidates[0]};
		# print Dumper(\%hash2);
		
		my $xGPS=$hash2{'location'}{'x'};
		my $yGPS=$hash2{'location'}{'y'};
		
		print $yGPS . ' / '.$xGPS ."\n"; # par convention, on écrit latitude,longitude
		return($xGPS,$yGPS);
		
		
	} else {
		print "Erreur ".$response->code." !!\n";
		exit;
	}
	
}

sub updateGPS {
	
	my ($codsoc,$sigtie,$gpsX,$gpsY)=@_;
	
	#my $valGeoloc=$gpsY.','.$gpsX; # par convention, on écrit latitude,longitude
	
	# my $rqt="update adr set typgeoloc=1,geoloc='$valGeoloc' where codsoc=$codsoc and sigadr='$sigtie' and typadr='COM' and numadr=1";
	my $rqt= "update adr set typgeoloc=1, gps_latitude='$gpsY', gps_longitude='$gpsX' where codsoc=$codsoc and sigadr='$sigtie' and typadr='COM' and numadr=1";
	my $sth = $dbh->prepare($rqt);
	$sth->execute() or print "Erreur de mise à jour...\n";
	
}

sub attente {
	my ($fixe,$variable)=@_;
	my $temp=int($fixe+rand($variable));
	sleep($temp);
}