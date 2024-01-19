# New Engineer Training

## Setup

1. Start the virtual machine

```
vagrant up
```

2. Connect to the virtual machine

```
vagrant ssh
```

3. Go to synced directory

```
cd vagrant
```

4. Install packages

```
composer install
```

5. Run migration

```
vendor/bin/phinx migrate
```
