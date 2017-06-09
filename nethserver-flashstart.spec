Summary: NethServer FlashStart integration
Name: nethserver-flashstart
Version: 1.1.9999
Release: 1%{?dist}
License: GPL
URL: %{url_prefix}/%{name}
Source0: %{name}-%{version}.tar.gz
BuildArch: noarch

Requires: nethserver-squid, nethserver-unbound

BuildRequires: perl, php-soap
BuildRequires: nethserver-devtools

%description
NethServer FlashStart integration.
See: http://www.flashstart.it/

%prep
%setup

%build
%{makedocs}
perl createlinks
for _nsdb in flashstart; do
   mkdir -p root/%{_nsdbconfdir}/${_nsdb}/{migrate,force,defaults}
done

%install
rm -rf %{buildroot}
(cd root; find . -depth -print | cpio -dump %{buildroot})
%{genfilelist} %{buildroot} > %{name}-%{version}-filelist
echo "%doc COPYING" >> %{name}-%{version}-filelist

%post

%preun

%files -f %{name}-%{version}-filelist
%defattr(-,root,root)
%dir %{_nseventsdir}/%{name}-update
%dir %{_nsdbconfdir}/flashstart

%changelog
