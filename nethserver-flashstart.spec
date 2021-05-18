Summary: NethServer FlashStart integration
Name: nethserver-flashstart
Version: 2.7.0
Release: 1%{?dist}
License: GPL
URL: %{url_prefix}/%{name}
Source0: %{name}-%{version}.tar.gz
# Execute prep-sources to create Source1
Source1: %{name}-cockpit.tar.gz
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

mkdir -p %{buildroot}/usr/share/cockpit/%{name}/
mkdir -p %{buildroot}/usr/share/cockpit/nethserver/applications/
mkdir -p %{buildroot}/usr/libexec/nethserver/api/%{name}/

tar xvf %{SOURCE1} -C %{buildroot}/usr/share/cockpit/%{name}/

cp -a %{name}.json %{buildroot}/usr/share/cockpit/nethserver/applications/
cp -a api/* %{buildroot}/usr/libexec/nethserver/api/%{name}/
chmod +x %{buildroot}/usr/libexec/nethserver/api/%{name}/*

%{genfilelist} %{buildroot} > %{name}-%{version}-filelist

%files -f %{name}-%{version}-filelist
%defattr(-,root,root)
%dir %{_nseventsdir}/%{name}-update
%dir %{_nsdbconfdir}/flashstart
%doc COPYING

%changelog
* Tue May 18 2021 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.7.0-1
- Flashstart: customizable portal URL - nethesis/dev#6018
- Delegation for Cockpit Flashstart application - Bug nethesis/dev#6016

* Tue May 11 2021 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.2.6-1
- Flashstart new URL - Nethesis/dev#6010

* Fri Dec 11 2020 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.2.5-1
- UI: change app description - Nethesis/nethserver-flashstart#15

* Wed Dec 09 2020 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.2.4-1
- Flashstart: various improvements - Nethesis/dev#5934

* Tue Jul 21 2020 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.2.3-1
- ui: fix dashboard and settings page loading (#12)

* Fri Jul 10 2020 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.2.2-1
- Cron email after Flashstart removal - Bug nethesis/dev#5841
- DNS Blacklists for threat shield   - NethServer/dev#6212

* Wed Jan 08 2020 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.2.1-1
- Flashstart Cockpit UI: add link to flashstart management page - nethesis/dev#5749

* Tue Aug 06 2019 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.2.0-1
- Flashstart Cockpit UI - Nethesis/dev#5689

* Tue Jul 09 2019 Davide Principi <davide.principi@nethesis.it> - 2.1.2-1
- Cockpit Enterprise legacy apps implementation - nethesis/dev#5667

* Thu Jan 17 2019 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 2.1.1-1
- flashstart: can't disable cloud content filter when the connection is down - Bug Nethesis/dev#5561
- weekly report: flashstart section not working - Bug Nethesis/dev#5560

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

