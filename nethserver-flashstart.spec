Summary: NethServer FlashStart integration
Name: nethserver-flashstart
Version: 2.1.0
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
* Mon May 28 2018 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.1.0-1
- Flashstart : add hotspot support - Nethesis/dev#5393

* Thu Apr 19 2018 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.0.4-1
- Remove old unused inventory plugin - Nethesis/dev#5354
- FlashStart: change upstream DNS in squid configuration - Nethesis/dev#5353

* Wed Feb 07 2018 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.0.3-1
- FlashStart: change upstream DNS - Nethesis/dev#5307

* Wed Jan 10 2018 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.0.2-1
- FlashStart: allow CIDR bypass - Nethesis/dev#5276

* Tue Oct 17 2017 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.0.1-1
- Add customizable UpdateInterval

* Fri Jun 23 2017 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.0.0-1
- New implementation based on unbound - Nethesis/dev#5138

